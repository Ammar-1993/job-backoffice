<?php

namespace App\Services\JobImport;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Pulls from We Work Remotely's public RSS feeds — official, no auth,
 * no scraping of rendered pages. Full feed list:
 * https://weworkremotely.com/remote-job-rss-feed
 *
 * WWR titles follow the convention "Company Name: Job Title", which is
 * how we split out the company below.
 */
class WeWorkRemotelyAdapter implements JobSourceAdapter
{
    public function fetch(): array
    {
        $results = [];

        foreach (config('job_sources.weworkremotely_feeds', []) as $feedUrl) {
            try {
                $response = Http::timeout(15)->get($feedUrl);

                if (! $response->successful()) {
                    Log::warning("WeWorkRemotelyAdapter: '{$feedUrl}' returned HTTP {$response->status()}");
                    continue;
                }

                $xml = @simplexml_load_string($response->body());
                if ($xml === false || ! isset($xml->channel->item)) {
                    Log::warning("WeWorkRemotelyAdapter: could not parse RSS from '{$feedUrl}'");
                    continue;
                }

                foreach ($xml->channel->item as $item) {
                    $rawTitle = trim((string) $item->title);
                    $link = trim((string) $item->link);

                    if ($rawTitle === '' || $link === '') {
                        continue;
                    }

                    [$companyName, $jobTitle] = $this->splitTitle($rawTitle);
                    $descriptionHtml = (string) $item->description;
                    $location = $this->extractLocation($descriptionHtml) ?? 'Remote';

                    // ── GCC & Worldwide targeting: skip regional restrictions like "USA Only" ──
                    if (! $this->isGccOrWorldwide($location)) {
                        continue;
                    }

                    $description = trim(strip_tags($descriptionHtml));

                    $results[] = [
                        'title' => $jobTitle,
                        'description' => $description !== '' ? $description : $jobTitle,
                        'location' => $location,
                        'type' => 'Remote', // every WWR listing is remote by definition
                        'salary'   => 'Not specified', // not exposed in the feed
                        'company_name' => $companyName,
                        'source_platform' => 'weworkremotely',
                        'source_url' => $link,
                        'external_id' => trim((string) ($item->guid ?? '')) ?: null,
                    ];
                }
            } catch (\Throwable $e) {
                Log::error("WeWorkRemotelyAdapter: failed for '{$feedUrl}' — {$e->getMessage()}");
            }
        }

        return $results;
    }

    /**
     * WWR titles look like "Kaleo Software: Senior Software Engineer".
     * Falls back gracefully if a listing doesn't follow the convention.
     */
    private function splitTitle(string $rawTitle): array
    {
        if (Str::contains($rawTitle, ': ')) {
            [$company, $title] = explode(': ', $rawTitle, 2);

            return [trim($company), trim($title)];
        }

        return ['Unknown Company', $rawTitle];
    }

    private function extractLocation(string $descriptionHtml): ?string
    {
        if (preg_match('/Location:<\/strong>\s*([^<]+)/i', $descriptionHtml, $m)) {
            return trim($m[1]);
        }

        return null;
    }

    private function isGccOrWorldwide(?string $loc): bool
    {
        if ($loc === null || trim($loc) === '' || strtolower(trim($loc)) === 'remote') {
            return true; // unrestricted remote
        }

        $locLower = Str::lower($loc);

        // Worldwide & GCC keywords
        $allowed = [
            'anywhere', 'worldwide', 'global', 'international',
            'middle east', 'mena', 'gulf', 'gcc',
            'saudi', 'riyadh', 'jeddah', 'uae', 'dubai', 'abu dhabi',
            'kuwait', 'qatar', 'bahrain', 'oman',
        ];
        foreach ($allowed as $kw) {
            if (str_contains($locLower, $kw)) {
                return true;
            }
        }

        // Exclude US/EU only restrictions
        $excluded = [
            'usa only', 'us only', 'united states only',
            'europe only', 'eu only', 'uk only', 'canada only',
            'latam only', 'americas only', 'australia only',
        ];
        foreach ($excluded as $kw) {
            if (str_contains($locLower, $kw)) {
                return false;
            }
        }

        return false;
    }
}
