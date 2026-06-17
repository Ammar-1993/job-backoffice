<?php

namespace App\Providers;

use App\Models\JobVacancy;
use App\Observers\JobVacancyObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // هذا الشرط يضمن أن يتم إجبار HTTPS فقط عند الرفع على السيرفر
        // ويحافظ على عمل المشروع محلياً بدون مشاكل
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Auto-generate vector embeddings whenever a job vacancy is created or updated.
        JobVacancy::observe(JobVacancyObserver::class);
    }
}


