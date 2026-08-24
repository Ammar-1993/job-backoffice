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
        // شركات تقنية كبرى تستخدم Greenhouse بلوحات عامة (لا تتطلب مصادقة)
        'gitlab'      => 'GitLab',
        'notion'      => 'Notion',
        'figma'       => 'Figma',
        'linear'      => 'Linear',
        'retool'      => 'Retool',
        'sourcegraph' => 'Sourcegraph',
        'gusto'       => 'Gusto',
        'intercom'    => 'Intercom',
        'loom'        => 'Loom',
        'zapier'      => 'Zapier',
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

    // System account used as the "owner" of auto-created Company records
    // for external listings (job_vacancies.companyId is a required FK).
    'system_importer_email' => env('JOB_IMPORTER_SYSTEM_EMAIL', 'importer@hireme-platform.online'),

    // Fallback JobCategory name used for every imported vacancy, since
    // job_vacancies.jobCategoryId is a required FK and external sources
    // don't map cleanly onto your existing categories.
    'default_job_category' => 'External Import',
];
