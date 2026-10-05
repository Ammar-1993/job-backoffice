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
                        ? $this->htmlToPlainText($job['content'])
                        : $title;

                    $location = $job['location']['name'] ?? 'Not specified';

                    // ── GCC & Gulf Targeting: only keep GCC-based jobs or Worldwide remote ──
                    if (! $this->isGccEligible($location, $title)) {
                        continue;
                    }

                    $results[] = [
                        'title'           => $title,
                        'description'     => $description !== '' ? $description : $title,
                        'location'        => $location,
                        'type'            => $this->detectType($title, $location),
                        'salary'          => 'Not specified', // Greenhouse's public board API doesn't expose pay data on most boards
                        'company_name'    => $companyName ?: $boardToken,
                        'source_platform' => 'greenhouse',
                        'source_url'      => $job['absolute_url'] ?? null,
                        'external_id'     => isset($job['id']) ? (string) $job['id'] : null,
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
            Str::contains($haystack, ['hybrid'])                              => 'Hybrid',
            Str::contains($haystack, ['remote', 'anywhere'])                 => 'Remote',
            Str::contains($haystack, ['contract', 'contractor', 'freelance']) => 'Contract',
            default                                                           => 'Full-Time',
        };
    }

    /**
     * Convert HTML job description to clean, readable plain text.
     *
     * Strategy:
     *   1. Inject newlines before/after block-level elements so structure is preserved.
     *   2. Prefix <li> items with a bullet character.
     *   3. Strip all remaining HTML tags.
     *   4. Decode HTML entities (&amp; &nbsp; &lt; etc.).
     *   5. Normalise whitespace (collapse blank lines, trim each line).
     */
    private function htmlToPlainText(string $html): string
    {
        // Decode HTML entities FIRST because Greenhouse returns escaped HTML (e.g. &lt;p&gt;)
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Inject newlines before opening block-level tags
        $html = preg_replace('/<(h[1-6]|p|div|br|hr|section|article)[^>]*>/i', "\n", $html);
        // Inject newlines after closing block-level tags
        $html = preg_replace('/<\/(h[1-6]|p|div|section|article)>/i', "\n", $html);
        // Bullet-point list items
        $html = preg_replace('/<li[^>]*>/i', "\n• ", $html);

        // Strip remaining HTML tags
        $text = strip_tags($html);

        // Decode HTML entities AGAIN in case of double-encoding or regular text entities
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Collapse 3+ consecutive newlines → 2 (preserve paragraph breaks)
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        // Trim whitespace from each line individually
        $lines = array_map('trim', explode("\n", $text));
        $text  = implode("\n", $lines);

        return trim($text);
    }

    /**
     * Determine if a job location is in the GCC / Arab Gulf region or Worldwide remote.
     *
     * Filters for:
     *  - Saudi Arabia 🇸🇦 (Riyadh, Jeddah, Dammam, Khobar, Dhahran, etc.)
     *  - United Arab Emirates 🇦🇪 (Dubai, Abu Dhabi, Sharjah, etc.)
     *  - Kuwait 🇰🇼, Qatar 🇶🇦, Bahrain 🇧🇭, Oman 🇴🇲
     *  - Middle East / MENA / Gulf
     *  - Explicit Worldwide / Global remote roles
     */
    public function isGccEligible(string $location, string $title = ''): bool
    {
        $haystack = Str::lower($location . ' ' . $title);

        $gccKeywords = [
            // Saudi Arabia 🇸🇦
            'saudi', 'ksa', 'riyadh', 'jeddah', 'dammam', 'khobar', 'dhahran', 'jubail', 'makkah', 'mecca', 'medina', 'tabuk',
            // United Arab Emirates 🇦🇪
            'uae', 'united arab emirates', 'dubai', 'abu dhabi', 'sharjah', 'ajman', 'ras al khaimah',
            // Kuwait 🇰🇼
            'kuwait',
            // Qatar 🇶🇦
            'qatar', 'doha',
            // Bahrain 🇧🇭
            'bahrain', 'manama',
            // Oman 🇴🇲
            'oman', 'muscat',
            // Regional MENA / Gulf
            'middle east', 'mena', 'gulf', 'gcc',
        ];

        foreach ($gccKeywords as $kw) {
            if (Str::contains($haystack, $kw)) {
                return true;
            }
        }

        // Worldwide / Global remote roles (open to applicants everywhere, including GCC)
        $locClean = Str::lower(trim($location));
        $worldwideKeywords = [
            'worldwide', 'remote (worldwide)', 'global', 'anywhere', 'international',
            'remote - worldwide', 'remote - global', 'remote — worldwide', 'remote — global',
        ];
        foreach ($worldwideKeywords as $kw) {
            if ($locClean === $kw || Str::startsWith($locClean, 'remote (worldwide')) {
                return true;
            }
        }

        return false;
    }
}
