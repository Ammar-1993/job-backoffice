<?php

namespace App\Services\JobImport;

use App\Enums\UserRole;
use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class JobImportService
{
    /** @param JobSourceAdapter[] $adapters */
    public function __construct(protected array $adapters)
    {
    }

    /**
     * Runs every configured adapter and upserts the results into
     * job_vacancies, keyed by source_url so re-running the command is
     * idempotent (existing listings get refreshed, not duplicated).
     *
     * @param int $limit Maximum number of vacancies to create/update in this
     *                    run (0 = no limit). Each create/update triggers a
     *                    real, billed OpenAI embedding call via
     *                    JobVacancyObserver — this exists to cap that cost
     *                    while testing, not to throttle the HTTP fetch step.
     * @return array{fetched:int,created:int,updated:int,skipped:int,failed:int,limited:bool}
     */
    public function run(int $limit = 0, int $minMatch = 0, ?array $candidateSkills = null, ?array $resumeEmbedding = null): array
    {
        $stats = ['fetched' => 0, 'created' => 0, 'updated' => 0, 'skipped' => 0, 'failed' => 0, 'limited' => false];

        $systemUser = $this->resolveSystemUser();
        $defaultCategory = JobCategory::firstOrCreate(
            ['name' => config('job_sources.default_job_category')]
        );

        // Fetch from every adapter first, then process as one flat list —
        // this is what lets the limit apply across sources, not per-source.
        $allItems = [];
        foreach ($this->adapters as $adapter) {
            $items = $adapter->fetch();
            $stats['fetched'] += count($items);
            $allItems = [...$allItems, ...$items];
        }

        foreach ($allItems as $item) {
            if ($limit > 0 && ($stats['created'] + $stats['updated']) >= $limit) {
                $stats['limited'] = true;
                break;
            }

            try {
                if (empty($item['source_url']) || empty($item['title'])) {
                    $stats['skipped']++;
                    continue;
                }

                // 1. Technical Role & Location/Visa Eligibility Pre-filter
                $eligibility = \App\Support\JobFilter::isEligible(
                    $item['title'],
                    $item['description'] ?? '',
                    $item['location'] ?? ''
                );

                if (! $eligibility['eligible']) {
                    $stats['skipped']++;
                    continue;
                }

                // Strict Pre-filter: if minMatch is requested, verify the job matches at least 1 candidate skill
                if ($minMatch > 0 && !empty($candidateSkills)) {
                    $quickSkillMatch = \App\Support\SkillMatcher::matchSkills($candidateSkills, $item['title'], $item['description']);
                    if ($quickSkillMatch['match_count'] === 0) {
                        $stats['skipped']++;
                        continue;
                    }
                }

                $company = Company::firstOrCreate(
                    ['name' => $item['company_name'] ?: 'Unknown Company'],
                    [
                        'address' => 'Not specified (external import)',
                        'industry' => 'Not specified',
                        'ownerId' => $systemUser->id,
                    ]
                );

                $job = JobVacancy::withTrashed()->where('source_url', $item['source_url'])->first();
                $isNew = ($job === null);

                $data = [
                    'title'           => Str::limit($item['title'], 250, ''),
                    'description'     => $item['description'],
                    'location'        => Str::limit($item['location'] ?: 'Not specified', 250, ''),
                    'salary'          => $item['salary'] ?: 'Not specified',
                    'type'            => $item['type'],
                    'jobCategoryId'   => $defaultCategory->id,
                    'companyId'       => $company->id,
                    'source_platform' => $item['source_platform'],
                    'external_id'     => $item['external_id'] ?? null,
                    'imported_at'     => now(),
                ];

                if ($job) {
                    if ($job->trashed()) {
                        $job->restore();
                    }
                    $job->update($data);
                } else {
                    $job = JobVacancy::create(array_merge(['source_url' => $item['source_url']], $data));
                }

                // Post-import verification: check full hybrid score against candidate resume
                if ($minMatch > 0 && !empty($candidateSkills)) {
                    $jobEmbedding = $job->vector_embedding ? json_decode($job->vector_embedding, true) : null;
                    $hybrid = \App\Support\SkillMatcher::computeHybridScore(
                        $resumeEmbedding,
                        $jobEmbedding,
                        $candidateSkills,
                        $job->title ?? '',
                        $job->description ?? ''
                    );

                    if ($hybrid['composite_score'] < $minMatch) {
                        if ($job->jobApplications()->exists()) {
                            $job->delete();
                        } else {
                            $job->forceDelete();
                        }
                        $stats['skipped']++;
                        continue;
                    }
                }

                $isNew ? $stats['created']++ : $stats['updated']++;
            } catch (\Throwable $e) {
                $stats['failed']++;
                report($e);
            }
        }

        return $stats;
    }

    /**
     * companies.ownerId is a required FK — every auto-created Company
     * needs an owner, so we use one fixed system account for all of them.
     */
    private function resolveSystemUser(): User
    {
        return User::firstOrCreate(
            ['email' => config('job_sources.system_importer_email')],
            [
                'name' => 'Job Importer (System)',
                'password' => Hash::make(Str::random(40)),
                'role' => UserRole::CompanyOwner,
                'is_active' => true,
            ]
        );
    }
}
