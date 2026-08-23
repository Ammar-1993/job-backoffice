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
                    $description = trim(strip_tags($descriptionHtml));

                    $results[] = [
                        'title' => $jobTitle,
                        'description' => $description !== '' ? $description : $jobTitle,
                        'location' => $this->extractLocation($descriptionHtml) ?? 'Remote',
                        'type' => 'Remote', // every WWR listing is remote by definition
                        'salary' => 'غير محدد', // not exposed in the feed
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

        return ['غير معروف', $rawTitle];
    }

    private function extractLocation(string $descriptionHtml): ?string
    {
        if (preg_match('/Location:<\/strong>\s*([^<]+)/i', $descriptionHtml, $m)) {
            return trim($m[1]);
        }

        return null;
    }
}
