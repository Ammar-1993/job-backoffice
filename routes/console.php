<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// ─── Default Laravel inspire command ──────────────────────────────────────
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ─── Automated Job Import Schedule ────────────────────────────────────────
//
// All commands run once per day during off-peak hours.
// --limit caps the number of NEW/UPDATED vacancies per run to control
// OpenAI embedding costs (each new/updated job = 1 real API call).
// The import itself always fetches ALL listings from the remote API
// first — the limit only restricts how many are written to the DB.
//
// To run manually at any time:
//   docker exec job_admin_web php artisan jobs:import-external --source=greenhouse --limit=50
//   docker exec job_admin_web php artisan jobs:import-external --source=weworkremotely --limit=20
//   docker exec job_admin_web php artisan jobs:import-external --source=adzuna --limit=100
//
// To check what is scheduled:
//   docker exec job_admin_web php artisan schedule:list

// Greenhouse — 10 Saudi & GCC tech leaders (Tamara, Careem, MinIO, Stripe, etc.) at 02:00 (strict >= 80% match only)
Schedule::command('jobs:import-external --source=greenhouse --limit=4 --min-match=80')
    ->dailyAt('02:00')
    ->withoutOverlapping()   // prevent a stuck previous run from spawning a second
    ->runInBackground()      // non-blocking: doesn't hold up other scheduled tasks
    ->appendOutputTo(storage_path('logs/scheduler-greenhouse.log'));

// WeWorkRemotely — 3 RSS feeds, up to 20 new/updated vacancies per day at 02:30 (strict >= 80% match only)
Schedule::command('jobs:import-external --source=weworkremotely --limit=2 --min-match=80')
    ->dailyAt('02:30')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/scheduler-weworkremotely.log'));

// Adzuna GCC — Saudi Arabia, UAE, Kuwait, Qatar at 03:00 (strict >= 80% match only)
Schedule::command('jobs:import-external --source=adzuna --limit=4 --min-match=80')
    ->dailyAt('03:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/scheduler-adzuna.log'));

// Remotive — 5 tech categories at 03:30 (strict >= 80% match only)
Schedule::command('jobs:import-external --source=remotive --limit=4 --min-match=80')
    ->dailyAt('03:30')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/scheduler-remotive.log'));
