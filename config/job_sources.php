<?php

return [

    /**
     * Greenhouse public Job Board API — https://boards-api.greenhouse.io/v1/boards/{token}/jobs
     * No authentication required for GET. Add the "board token" for any
     * company that uses Greenhouse and keeps its public board enabled
     * (found in their careers URL: boards.greenhouse.io/{token}).
     */
    // Map: board token => the company display name you want stored/shown.
    'greenhouse_boards' => [
        // ── Verified GCC Tech Leaders & Unicorns (100% Real GCC Jobs) 🇸🇦 🇦🇪 ──
        'tamara'      => 'Tamara',        // 21 jobs in Riyadh (Saudi FinTech Unicorn)
        'careem'      => 'Careem',        // 11 jobs in Dubai (Ride-hailing & Super App)
        'deliveroo'   => 'Deliveroo',     // 16 jobs in Dubai, Kuwait, Qatar
        'bybit'       => 'Bybit',         // 56 jobs in Dubai (FinTech / Crypto)
        'okx'         => 'OKX',           // 7 jobs in Dubai
        'monks'       => 'Monks',         // 6 jobs in Riyadh & Dubai (Tech Agency)
        'minio'       => 'MinIO',         // 3 jobs in Riyadh (Storage Tech)
        'nebius'      => 'Nebius',        // 3 jobs in Dubai & Abu Dhabi (AI Cloud)
        'stripe'      => 'Stripe',        // 3 jobs in Dubai & Riyadh (Payments)
        'braze'       => 'Braze',         // 1 job in Dubai (Customer Engagement)
    ],

    /**
     * We Work Remotely public RSS feeds — no auth, ToS-safe.
     * Full list: https://weworkremotely.com/remote-job-rss-feed
     */
    'weworkremotely_feeds' => [
        'https://weworkremotely.com/categories/remote-full-stack-programming-jobs.rss',
        'https://weworkremotely.com/categories/remote-back-end-programming-jobs.rss',
        'https://weworkremotely.com/categories/remote-front-end-programming-jobs.rss',
    ],

    /**
     * Adzuna REST API — https://developer.adzuna.com/
     * Sign up FREE at: https://developer.adzuna.com/signup
     * After registration you receive an app_id and app_key.
     *
     * Supported GCC country codes:
     *   sa = Saudi Arabia 🇸🇦
     *   ae = United Arab Emirates 🇦🇪
     *   kw = Kuwait 🇰🇼
     *   qa = Qatar 🇶🇦
     *   bh = Bahrain 🇧🇭
     *   om = Oman 🇴🇲
     *
     * NOTE: Adzuna does not list all GCC countries separately — if a country
     * returns empty results, it likely lacks a dedicated Adzuna market.
     * Saudi Arabia (sa) and UAE (ae) are the most populated.
     */
    'adzuna_app_id'  => env('ADZUNA_APP_ID'),
    'adzuna_app_key' => env('ADZUNA_APP_KEY'),

    /**
     * Search queries to run per country market.
     * Each entry fetches up to (max_pages × 50) listings.
     *
     * 'what'      → keyword(s) passed to Adzuna's ?what= param (leave empty for all jobs)
     * 'country'   → ISO 3166-1 alpha-2 country code (see supported codes above)
     * 'max_pages' → how many pages to fetch (1 page = up to 50 results)
     */
    'adzuna_searches' => [
        // ── Adzuna: البحث عبر كلمات مفتاحية جغرافية في السوق الأمريكي/البريطاني ──
        // (دول الخليج لا تملك سوق Adzuna مستقل — نستخدم us/gb مع كلمات الاستهداف)
        ['country' => 'us', 'what' => 'Saudi Arabia',              'max_pages' => 2],
        ['country' => 'gb', 'what' => 'Saudi Arabia',              'max_pages' => 2],
        ['country' => 'us', 'what' => 'Riyadh',                    'max_pages' => 2],
        ['country' => 'gb', 'what' => 'Riyadh',                    'max_pages' => 1],
        ['country' => 'us', 'what' => 'Dubai',                     'max_pages' => 2],
        ['country' => 'gb', 'what' => 'Dubai',                     'max_pages' => 1],
        ['country' => 'us', 'what' => 'UAE',                       'max_pages' => 1],
        ['country' => 'us', 'what' => 'Kuwait',                    'max_pages' => 1],
        ['country' => 'us', 'what' => 'Qatar',                     'max_pages' => 1],
        ['country' => 'us', 'what' => 'MENA developer',            'max_pages' => 1],
        ['country' => 'gb', 'what' => 'Middle East engineer',      'max_pages' => 1],
    ],

    /**
     * Remotive public JSON API — https://remotive.com/api-documentation
     *
     * ✅ 100% free — no API key or registration required.
     * ✅ Official API — not scraping.
     * ✅ Rate limit: max ~4 requests/day (we run once at 03:30).
     * ✅ Clean JSON with structured fields.
     * ✅ All jobs are remote-first, sourced globally.
     *
     * Endpoint: GET https://remotive.com/api/remote-jobs
     *   ?category=<slug>   → filter by job category (see full slug list below)
     *   ?limit=N           → max results returned
     *
     * Available category slugs (relevant to tech/engineering):
     *   software-development, artificial-intelligence, data,
     *   devops, information-technology, engineering, product,
     *   project-management
     *
     * ToS requirement: link back to the Remotive URL and credit Remotive.
     * The source_url we store IS the canonical Remotive URL, satisfying this.
     */
    'remotive_searches' => [
        // ── Remotive: نبحث عن الوظائف "Worldwide" فقط لأنها قابلة للتقديم من الخليج ──
        // الوظائف المقيّدة بـ USA/Europe لا قيمة لها للمتقدم الخليجي.
        // نستخدم limit صغير لأن Remotive يعيد بيانات أيام قليلة فقط (24h delay).
        ['category' => 'software-development',   'limit' => 100, 'location_filter' => 'worldwide'],
        ['category' => 'artificial-intelligence',  'limit' => 50,  'location_filter' => 'worldwide'],
        ['category' => 'devops',                  'limit' => 50,  'location_filter' => 'worldwide'],
        ['category' => 'data',                    'limit' => 50,  'location_filter' => 'worldwide'],
        ['category' => 'engineering',             'limit' => 50,  'location_filter' => 'worldwide'],
    ],

    // System account used as the "owner" of auto-created Company records
    // for external listings (job_vacancies.companyId is a required FK).
    'system_importer_email' => env('JOB_IMPORTER_SYSTEM_EMAIL', 'importer@hireme-platform.online'),

    // Fallback JobCategory name used for every imported vacancy, since
    // job_vacancies.jobCategoryId is a required FK and external sources
    // don't map cleanly onto your existing categories.
    'default_job_category' => 'External Import',
];

