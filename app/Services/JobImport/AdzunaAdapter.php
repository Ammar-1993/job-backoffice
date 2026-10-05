<?php

namespace App\Services\JobImport;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Pulls job listings from Adzuna's official REST API.
 *
 * Adzuna supports multiple country markets. We target GCC countries:
 *   - sa  → Saudi Arabia (🇸🇦)
 *   - ae  → United Arab Emirates (🇦🇪)
 *   - kw  → Kuwait (🇰🇼)
 *   - qa  → Qatar (🇶🇦)
 *   - bh  → Bahrain (🇧🇭)
 *   - om  → Oman (🇴🇲)
 *
 * API endpoint pattern:
 *   GET https://api.adzuna.com/v1/api/jobs/{country}/search/{page}
 *       ?app_id=XXX&app_key=YYY&results_per_page=50&what=developer
 *
 * Docs:    https://developer.adzuna.com/overview
 * Sign up: https://developer.adzuna.com/signup  (free — no credit card)
 *
 * Required .env keys:
 *   ADZUNA_APP_ID=your_app_id
 *   ADZUNA_APP_KEY=your_app_key
 */
class AdzunaAdapter implements JobSourceAdapter
{
    /**
     * Base URL for the Adzuna v1 API.
     */
    private const BASE_URL = 'https://api.adzuna.com/v1/api/jobs';

    /**
     * Number of results to request per API call (max 50 per page).
     */
    private const RESULTS_PER_PAGE = 50;

    public function fetch(): array
    {
        $appId  = config('job_sources.adzuna_app_id');
        $appKey = config('job_sources.adzuna_app_key');

        if (! $appId || ! $appKey) {
            Log::warning('AdzunaAdapter: ADZUNA_APP_ID or ADZUNA_APP_KEY is not set in .env — skipping.');
            return [];
        }

        $results = [];

        // Iterate over every configured country × search term combination
        foreach (config('job_sources.adzuna_searches', []) as $search) {
            $country    = $search['country']      ?? 'sa';   // ISO 3166-1 alpha-2
            $what       = $search['what']         ?? '';     // keyword(s) to search
            $maxPages   = $search['max_pages']    ?? 1;      // pages to fetch (50 results each)

            for ($page = 1; $page <= $maxPages; $page++) {
                try {
                    $response = Http::timeout(20)->get(
                        self::BASE_URL . "/{$country}/search/{$page}",
                        [
                            'app_id'           => $appId,
                            'app_key'          => $appKey,
                            'results_per_page' => self::RESULTS_PER_PAGE,
                            'what'             => $what,
                            'content-type'     => 'application/json',
                            'sort_by'          => 'date',
                        ]
                    );

                    if (! $response->successful()) {
                        Log::warning("AdzunaAdapter: [{$country}] '{$what}' page {$page} — HTTP {$response->status()}");
                        break; // no point fetching more pages from a failing endpoint
                    }

                    $jobs = $response->json('results', []);

                    if (empty($jobs)) {
                        break; // no more results on this page
                    }

                    foreach ($jobs as $job) {
                        $title = $job['title'] ?? null;
                        $url   = $job['redirect_url'] ?? null;

                        if (! $title || ! $url) {
                            continue;
                        }

                        $location = $this->extractLocation($job, $country);

                        // ── GCC Targeting: only keep jobs actually in GCC countries ──
                        if (! $this->isGccEligible($location, $title)) {
                            continue;
                        }

                        $results[] = [
                            'title'           => $this->cleanTitle($title),
                            'description'     => $this->buildDescription($job),
                            'location'        => $location,
                            'type'            => $this->detectType($title, $job['contract_time'] ?? '', $job['contract_type'] ?? ''),
                            'salary'          => $this->formatSalary($job),
                            'company_name'    => $job['company']['display_name'] ?? 'Unknown Company',
                            'source_platform' => 'adzuna',
                            'source_url'      => $url,
                            'external_id'     => (string) ($job['id'] ?? md5($url)),
                        ];
                    }
                } catch (\Throwable $e) {
                    Log::error("AdzunaAdapter: failed for [{$country}] '{$what}' page {$page} — {$e->getMessage()}");
                    break;
                }
            }
        }

        return $results;
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    /**
     * Remove any HTML from the job title (Adzuna occasionally returns it).
     */
    private function cleanTitle(string $title): string
    {
        return Str::limit(html_entity_decode(strip_tags($title), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 250, '');
    }

    /**
     * Build a clean plain-text description from available Adzuna fields.
     * Adzuna's free tier returns a 'description' snippet (≈ 200 chars) —
     * we enrich it with category, salary hints, and location context.
     */
    private function buildDescription(array $job): string
    {
        $parts = [];

        $description = $job['description'] ?? '';
        if ($description) {
            $parts[] = html_entity_decode(strip_tags($description), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        if (! empty($job['category']['label'])) {
            $parts[] = "Category: {$job['category']['label']}";
        }

        if (! empty($job['contract_type'])) {
            $parts[] = 'Contract type: ' . ucfirst(str_replace('_', ' ', $job['contract_type']));
        }

        if (! empty($job['contract_time'])) {
            $parts[] = 'Hours: ' . ucfirst(str_replace('_', ' ', $job['contract_time']));
        }

        $salary = $this->formatSalary($job);
        if ($salary && $salary !== 'Not specified') {
            $parts[] = "Salary: {$salary}";
        }

        if (! empty($job['company']['display_name'])) {
            $parts[] = "Company: {$job['company']['display_name']}";
        }

        return implode("\n\n", array_filter($parts)) ?: ($job['title'] ?? 'No description available');
    }

    /**
     * Extract a human-readable location string from the Adzuna response.
     * Falls back gracefully to the country name if no location area is given.
     */
    private function extractLocation(array $job, string $countryCode): string
    {
        $countryNames = [
            'sa' => 'Saudi Arabia',
            'ae' => 'United Arab Emirates',
            'kw' => 'Kuwait',
            'qa' => 'Qatar',
            'bh' => 'Bahrain',
            'om' => 'Oman',
            'gb' => 'United Kingdom',
            'us' => 'United States',
        ];

        $locationParts = [];

        // Adzuna nests location as: location.area = ['Country', 'Region', 'City', ...]
        if (! empty($job['location']['area'])) {
            $areas = array_filter($job['location']['area']); // remove empty strings
            // Usually: [0]=Country [1]=Region [2]=City — we want City, Region
            $city   = $areas[array_key_last($areas)] ?? null;
            $region = count($areas) >= 2 ? $areas[array_key_last($areas) - 1] : null;

            if ($city && $city !== ($countryNames[$countryCode] ?? '')) {
                $locationParts[] = $city;
            }
            if ($region && $region !== $city && $region !== ($countryNames[$countryCode] ?? '')) {
                $locationParts[] = $region;
            }
        }

        // Add the country name as final context
        $locationParts[] = $countryNames[$countryCode] ?? strtoupper($countryCode);

        return Str::limit(implode(', ', array_unique($locationParts)), 250, '');
    }

    /**
     * Map Adzuna's contract_time / contract_type to the DB ENUM values.
     * Allowed values: Remote, Hybrid, Full-Time, Contract
     */
    private function detectType(string $title, string $contractTime, string $contractType): string
    {
        $haystack = Str::lower("{$title} {$contractTime} {$contractType}");

        return match (true) {
            Str::contains($haystack, ['remote', 'anywhere'])                  => 'Remote',
            Str::contains($haystack, ['hybrid'])                               => 'Hybrid',
            Str::contains($haystack, ['contract', 'freelance', 'temporary'])   => 'Contract',
            default                                                             => 'Full-Time',
        };
    }

    /**
     * Format salary from Adzuna's min/max fields into a readable string.
     * Adzuna returns values in the local currency (SAR for Saudi Arabia, AED for UAE, etc.)
     */
    private function formatSalary(array $job): string
    {
        $min = $job['salary_min'] ?? null;
        $max = $job['salary_max'] ?? null;

        if ($min && $max) {
            return number_format($min, 0) . ' – ' . number_format($max, 0);
        }

        if ($min) {
            return 'From ' . number_format($min, 0);
        }

        if ($max) {
            return 'Up to ' . number_format($max, 0);
        }

        return 'Not specified';
    }

    private function isGccEligible(string $location, string $title): bool
    {
        $haystack = Str::lower($location . ' ' . $title);

        $gccKeywords = [
            'saudi', 'ksa', 'riyadh', 'jeddah', 'dammam', 'khobar', 'dhahran', 'jubail',
            'uae', 'united arab emirates', 'dubai', 'abu dhabi', 'sharjah',
            'kuwait', 'qatar', 'doha', 'bahrain', 'manama', 'oman', 'muscat',
            'middle east', 'mena', 'gulf', 'gcc',
        ];

        foreach ($gccKeywords as $kw) {
            if (str_contains($haystack, $kw)) {
                // Must NOT be located in US or UK
                $locLower = Str::lower($location);
                if (! str_contains($locLower, 'united states') && ! str_contains($locLower, 'united kingdom')) {
                    return true;
                }
            }
        }

        return false;
    }
}
