<?php

namespace App\Console\Commands;

use App\Models\JobVacancy;
use App\Models\User;
use App\Support\SkillMatcher;
use Illuminate\Console\Command;

class PurgeUnmatchedJobs extends Command
{
    protected $signature = 'jobs:purge-unmatched
        {--min-score=80 : الحد الأدنى لنسبة التطابق لإبقاء الوظيفة (افتراضياً 80%)}
        {--only-ineligible : حذف الوظائف غير التقنية والمحظورة جغرافياً وتأشيرياً فقط دون فحص نسبة التطابق}
        {--force-ineligible : حذف الوظائف غير التقنية والمحظورة حتى لو كان عليها طلب تجريبي شخصي سابق}
        {--email=ammaralnggar@gmail.com : بريد المرشح المعتمد للمطابقة}
        {--dry-run : تجربة وهمية فقط دون حذف فعلي}';

    protected $description = 'يحذف الوظائف المستوردة غير التقنية أو المقيدة جغرافياً أو غير المطابقة لسيرة المرشح';

    public function handle(): int
    {
        $minScore        = (int) $this->option('min-score');
        $onlyIneligible  = (bool) $this->option('only-ineligible');
        $forceIneligible = (bool) $this->option('force-ineligible');
        $email           = $this->option('email');
        $dryRun          = (bool) $this->option('dry-run');

        $candidateSkills = [];
        $resumeEmbedding = null;

        if (!$onlyIneligible) {
            $user = User::where('email', $email)->first() ?? User::where('role', 'job_seeker')->first();
            if (!$user) {
                $this->error("المستخدم بالبريد {$email} غير موجود.");
                return self::FAILURE;
            }

            $resume = $user->activeResume ?? $user->resumes()->latest()->first();
            if (!$resume) {
                $this->error("لا توجد سيرة ذاتية نشطة للمستخدم {$user->name}.");
                return self::FAILURE;
            }

            $candidateSkills = $resume->skills ?? [];
            $candidateExp    = $resume->experience ?? null;
            $resumeEmbedding = $resume->vector_embedding ? json_decode($resume->vector_embedding, true) : null;

            $this->info("المرشح: {$user->name} ({$user->email})");
            $this->info("عدد المهارات المستخرجة: " . count($candidateSkills));
            $this->info("عتبة الاستبعاد: أقل من {$minScore}%");
        } else {
            $this->info("🎯 وضع التنظيف الفوري: استبعاد الوظائف غير التقنية وتلك المقيدة بتأشيرات/مواقع محظورة فقط.");
        }

        if ($dryRun) {
            $this->warn("⚠ تشغيل تجريبي (Dry Run) — لن يتم حذف أي سجل فعلياً.");
        }

        // Query vacancies
        if ($forceIneligible) {
            $vacancies = JobVacancy::whereNotNull('source_platform')->get();
        } else {
            $vacancies = JobVacancy::whereNotNull('source_platform')
                ->whereDoesntHave('jobApplications', fn($q) => $q->where('is_personal', true))
                ->get();
        }

        $keptCount = 0;
        $purgedCount = 0;

        foreach ($vacancies as $job) {
            // 1. Check JobFilter eligibility first
            $eligibility = \App\Support\JobFilter::isEligible($job->title ?? '', $job->description ?? '', $job->location ?? '');
            if (!$eligibility['eligible']) {
                $purgedCount++;
                $this->line("  [-] استبعاد: {$job->title} ({$job->location}) => {$eligibility['reason']}");
                if (!$dryRun) {
                    $job->jobApplications()->delete();
                    $job->delete();
                }
                continue;
            }

            // 2. If only cleaning ineligible jobs, keep it
            if ($onlyIneligible) {
                $keptCount++;
                continue;
            }

            // 3. Check hybrid match score
            $jobEmbedding = $job->vector_embedding ? json_decode($job->vector_embedding, true) : null;
            $hybrid = SkillMatcher::computeHybridScore(
                $resumeEmbedding,
                $jobEmbedding,
                $candidateSkills,
                $job->title ?? '',
                $job->description ?? '',
                $candidateExp ?? null
            );

            $score = $hybrid['composite_score'];

            if ($score < $minScore) {
                $purgedCount++;
                if (!$dryRun) {
                    $job->delete();
                }
            } else {
                $keptCount++;
            }
        }

        $this->table(
            ['إجمالي الوظائف المفحوصة', "مطابقة (مُبقاة >= {$minScore}%)", "غير مطابقة (مُستبعدة < {$minScore}%)"],
            [[$vacancies->count(), $keptCount, $purgedCount]]
        );

        if ($dryRun) {
            $this->info("تمت المحاكاة: سيتم حذف {$purgedCount} وظيفة غير مطابقة عند التشغيل الفعلي.");
        } else {
            $this->info("✅ تم بنجاح حذف {$purgedCount} وظيفة غير مطابقة وتنظيف قاعدة البيانات لتبقى فقط وظائف الـ {$minScore}% فما فوق!");
        }

        return self::SUCCESS;
    }
}
