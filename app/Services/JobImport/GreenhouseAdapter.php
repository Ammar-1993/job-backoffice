<?php

namespace App\Services\JobImport;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Pulls from Greenhouse's public Job Board API — no auth required for GET.
 * GET https://boards-api.greenhouse.io/v1/boards/{board_token}/jobs?content=true
 *
 * Legit and ToS-safe: this is the endpoint Greenhouse itself designed for
 * building external career pages, not a scrape of protected content.
 */
class GreenhouseAdapter implements JobSourceAdapter
{
    public function fetch(): array
    {
        $results = [];

        foreach (config('job_sources.greenhouse_boards', []) as $boardToken => $companyName) {
            try {
                $response = Http::timeout(15)->get(
                    "https://boards-api.greenhouse.io/v1/boards/{$boardToken}/jobs",
                    ['content' => 'true']
                );

                if (! $response->successful()) {
                    Log::warning("GreenhouseAdapter: board '{$boardToken}' returned HTTP {$response->status()}");
                    continue;
                }

                foreach ($response->json('jobs', []) as $job) {
                    $title = $job['title'] ?? null;
                    if (! $title) {
                        continue; // skip malformed entries
                    }

                    $description = isset($job['content'])
                        ? trim(strip_tags($job['content']))
                        : $title;

                    $location = $job['location']['name'] ?? 'غير محدد';

                    $results[] = [
                        'title' => $title,
                        'description' => $description !== '' ? $description : $title,
                        'location' => $location,
                        'type' => $this->detectType($title, $location),
                        'salary' => 'غير محدد', // Greenhouse's public board API doesn't expose pay data on most boards
                        'company_name' => $companyName ?: $boardToken,
                        'source_platform' => 'greenhouse',
                        'source_url' => $job['absolute_url'] ?? null,
                        'external_id' => isset($job['id']) ? (string) $job['id'] : null,
                    ];
                }
            } catch (\Throwable $e) {
                Log::error("GreenhouseAdapter: failed for board '{$boardToken}' — {$e->getMessage()}");
            }
        }

        return $results;
    }

    private function detectType(string $title, string $location): string
    {
        // job_vacancies.type is a MySQL ENUM restricted to these 4 values —
        // anything else is mapped down rather than passed through, or the
        // insert fails at the DB layer.
        $haystack = Str::lower($title.' '.$location);

        return match (true) {
            Str::contains($haystack, ['hybrid']) => 'Hybrid',
            Str::contains($haystack, ['remote', 'anywhere']) => 'Remote',
            Str::contains($haystack, ['contract', 'contractor', 'freelance']) => 'Contract',
            default => 'Full-Time',
        };
    }
}
