<?php

namespace App\Services\JobImport;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Pulls remote job listings from Remotive's official public API.
 *
 * API Documentation: https://remotive.com/api-documentation
 * Endpoint: GET https://remotive.com/api/remote-jobs
 *
 * Key points:
 *  - 100% free, no API key or registration required.
 *  - Returns clean JSON with structured fields.
 *  - Rate limit: max ~4 requests/day recommended.
 *  - Jobs are delayed 24 hours vs the live site (for attribution).
 *  - Per ToS: link back to the Remotive URL and credit Remotive as source.
 *
 * Supported query params:
 *   ?category=software-development   → filter by job category slug
 *   ?limit=N                         → max number of results
 *   ?search=keyword                  → full-text search
 *
 * Full category list: https://remotive.com/api/remote-jobs/categories
 *
 * No .env keys needed — the API is completely open.
 */
class RemotiveAdapter implements JobSourceAdapter
{
    private const BASE_URL = 'https://remotive.com/api/remote-jobs';

    public function fetch(): array
    {
        $results = [];

        foreach (config('job_sources.remotive_searches', []) as $search) {
            $category       = $search['category']        ?? null;   // category slug
            $limit          = $search['limit']           ?? 100;    // max results
            $locationFilter = $search['location_filter'] ?? null;   // 'worldwide' | null
            $label          = $category ?? 'all';

            try {
                $params = ['limit' => $limit];
                if ($category) {
                    $params['category'] = $category;
                }

                $response = Http::timeout(20)
                    ->withHeaders(['Accept' => 'application/json'])
                    ->get(self::BASE_URL, $params);

                if (! $response->successful()) {
                    Log::warning("RemotiveAdapter: category='{$label}' — HTTP {$response->status()}");
                    continue;
                }

                $jobs = $response->json('jobs', []);

                if (empty($jobs)) {
                    Log::info("RemotiveAdapter: no jobs returned for category='{$label}'.");
                    continue;
                }

                $fetched = 0;
                foreach ($jobs as $job) {
                    $id    = $job['id']  ?? null;
                    $title = $job['title'] ?? null;
                    $url   = $job['url']   ?? null;

                    if (! $title || ! $url) {
                        continue;
                    }

                    // ── GCC Targeting: skip listings that restrict to USA/EU only ──
                    // If location_filter = 'worldwide', we only keep jobs that are
                    // Worldwide OR have no restriction OR mention GCC/MENA keywords.
                    if ($locationFilter === 'worldwide') {
                        $candidateLoc = strtolower($job['candidate_required_location'] ?? '');
                        $isGccFriendly = $this->isGccFriendlyLocation($candidateLoc);
                        if (! $isGccFriendly) {
                            continue; // skip USA/Europe-only roles
                        }
                    }

                    $results[] = [
                        'title'           => $this->cleanText($title),
                        'description'     => $this->buildDescription($job),
                        'location'        => $this->extractLocation($job),
                        'type'            => $this->detectType($job),
                        'salary'          => $this->formatSalary($job),
                        'company_name'    => $job['company_name'] ?? 'Unknown Company',
                        'source_platform' => 'remotive',
                        'source_url'      => $url,
                        'external_id'     => $id ? (string) $id : md5($url),
                    ];
                    $fetched++;
                }

                Log::info("RemotiveAdapter: kept {$fetched}/" . count($jobs) . " jobs for category='{$label}' (filter={$locationFilter}).");

            } catch (\Throwable $e) {
                Log::error("RemotiveAdapter: failed for category='{$label}' — {$e->getMessage()}");
            }
        }

        return $results;
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    /**
     * Strip HTML tags and trim whitespace from any text field.
     */
    private function cleanText(string $text): string
    {
        return Str::limit(
            trim(html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
            250,
            ''
        );
    }

    /**
     * Build a clean plain-text description.
     * Remotive's 'description' field contains HTML — we strip it.
     */
    private function buildDescription(array $job): string
    {
        $parts = [];

        $raw = $job['description'] ?? '';
        if ($raw) {
            // Replace block-level tags with newlines before stripping
            $clean = preg_replace('#<(br|/p|/li|/h[1-6]|/div|/section)[^>]*>#i', "\n", $raw);
            $clean = strip_tags($clean ?? '');
            $clean = html_entity_decode($clean, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $clean = preg_replace('/\n{3,}/', "\n\n", trim($clean));
            if ($clean) {
                $parts[] = $clean;
            }
        }

        if (! empty($job['category'])) {
            $parts[] = "Category: {$job['category']}";
        }

        if (! empty($job['tags'])) {
            $tags = is_array($job['tags']) ? implode(', ', $job['tags']) : $job['tags'];
            if ($tags) {
                $parts[] = "Skills: {$tags}";
            }
        }

        if (! empty($job['company_name'])) {
            $parts[] = "Company: {$job['company_name']}";
        }

        return implode("\n\n", array_filter($parts)) ?: ($job['title'] ?? 'No description available');
    }

    /**
     * Extract location from 'candidate_required_location'.
     * Remotive always lists remote jobs, so we prepend "Remote" if the field
     * is empty, or keep the geographic restriction if specified.
     */
    private function extractLocation(array $job): string
    {
        $loc = trim($job['candidate_required_location'] ?? '');

        if ($loc === '' || strtolower($loc) === 'worldwide') {
            return 'Remote (Worldwide)';
        }

        return Str::limit("Remote — {$loc}", 250, '');
    }

    /**
     * Map Remotive's job_type to the DB ENUM values.
     * All Remotive listings are remote by nature.
     */
    private function detectType(array $job): string
    {
        $jobType = strtolower($job['job_type'] ?? '');

        return match (true) {
            str_contains($jobType, 'contract') || str_contains($jobType, 'freelance') => 'Contract',
            str_contains($jobType, 'part')                                             => 'Full-Time', // closest enum
            default                                                                    => 'Remote',
        };
    }

    /**
     * Format salary — Remotive returns a free-text 'salary' field.
     */
    private function formatSalary(array $job): string
    {
        $salary = trim($job['salary'] ?? '');

        return $salary !== '' ? Str::limit($salary, 250, '') : 'Not specified';
    }

    /**
     * Determine if a Remotive job's location restriction is GCC-friendly.
     *
     * GCC applicants (Saudi Arabia, UAE, etc.) can apply to:
     *  ✅ Empty / Worldwide / Any location
     *  ✅ Jobs explicitly mentioning MENA/Gulf/Middle East
     *  ❌ Jobs restricted to USA, Canada, UK, Europe, Australia, etc.
     *
     * @param string $loc  lowercased candidate_required_location value
     */
    private function isGccFriendlyLocation(string $loc): bool
    {
        // Empty = no restriction = worldwide = GCC-friendly
        if ($loc === '') {
            return true;
        }

        // Explicitly GCC/MENA-positive keywords
        $gccKeywords = [
            'worldwide', 'global', 'anywhere', 'international',
            'remote', 'any country', 'all countries',
            'middle east', 'mena', 'gulf', 'gcc',
            'saudi', 'riyadh', 'jeddah',
            'uae', 'dubai', 'abu dhabi',
            'kuwait', 'qatar', 'bahrain', 'oman',
        ];
        foreach ($gccKeywords as $kw) {
            if (str_contains($loc, $kw)) {
                return true;
            }
        }

        // Hard-exclude restrictive USA/EU/AU/LATAM-only patterns
        $excludedKeywords = [
            'usa', 'united states', 'us only', 'u.s.',
            'canada', 'us and canada',
            'uk only', 'united kingdom', 'great britain',
            'europe only', 'eu only', 'european union',
            'australia', 'new zealand',
            'latin america', 'latam', 'south america',
            'asia pacific', 'apac',
        ];
        foreach ($excludedKeywords as $kw) {
            if (str_contains($loc, $kw)) {
                return false;
            }
        }

        // If location is specified but contains neither Worldwide nor GCC keywords,
        // it is restricted to other specific countries/regions — skip it.
        return false;
    }
}
