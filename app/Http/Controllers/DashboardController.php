<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobVacancy;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', 'all_time');

        if(auth()->user()->role == 'admin'){
            $analytics = $this->adminDashboard($range);
        } else {
            $analytics = $this->companyOwnerDashboard($range);
        }

        return view('dashboard.index', compact(['analytics', 'range']));
    }

    private function getDateLimit($range)
    {
        return match ($range) {
            'today' => now()->startOfDay(),
            'this_week' => now()->startOfWeek(),
            'this_month' => now()->startOfMonth(),
            'this_year' => now()->startOfYear(),
            default => null, // all_time
        };
    }

    private function getRangeLabel($range)
    {
        return match ($range) {
            'today' => __('app.dashboard.today') ?? 'Today',
            'this_week' => __('app.dashboard.this_week') ?? 'This Week',
            'this_month' => __('app.dashboard.this_month') ?? 'This Month',
            'this_year' => __('app.dashboard.this_year') ?? 'This Year',
            default => __('app.dashboard.all_time') ?? 'All Time',
        };
    }

    private function adminDashboard($range)
    {
        $dateLimit = $this->getDateLimit($range);
        $rangeLabel = $this->getRangeLabel($range);

        // Active users (job_seeker role)
        $activeUsers = Cache::remember("admin_active_users_{$range}", 600, function () use ($dateLimit) {
            $query = User::where('role', 'job_seeker');
            if ($dateLimit) $query->where('last_login_at', '>=', $dateLimit);
            else $query->where('last_login_at', '>=', now()->subDays(30)); // fallback to last 30 days for all_time
            return $query->count();
        });

        // Total jobs
        $totalJobs = Cache::remember("admin_total_jobs_{$range}", 600, function () use ($dateLimit) {
            $query = JobVacancy::whereNull('deleted_at');
            if ($dateLimit) $query->where('created_at', '>=', $dateLimit);
            return $query->count();
        });

        // Total applications
        $totalApplications = Cache::remember("admin_total_applications_{$range}", 600, function () use ($dateLimit) {
            $query = JobApplication::whereNull('deleted_at');
            if ($dateLimit) $query->where('created_at', '>=', $dateLimit);
            return $query->count();
        });

        // Total Companies
        $totalCompanies = Cache::remember("admin_total_companies_{$range}", 600, function () use ($dateLimit) {
            $query = \App\Models\Company::whereNull('deleted_at');
            if ($dateLimit) $query->where('created_at', '>=', $dateLimit);
            return $query->count();
        });

        // Average AI Match Score
        $avgAiScore = Cache::remember("admin_avg_ai_score_{$range}", 600, function () use ($dateLimit) {
            $query = JobApplication::whereNotNull('aiGeneratedScore')->whereNull('deleted_at');
            if ($dateLimit) $query->where('created_at', '>=', $dateLimit);
            return round((float) $query->avg('aiGeneratedScore'), 1);
        });

        // Closed Jobs
        $closedJobs = Cache::remember("admin_closed_jobs_{$range}", 600, function () use ($dateLimit) {
            $query = JobVacancy::onlyTrashed();
            if ($dateLimit) $query->where('deleted_at', '>=', $dateLimit);
            return $query->count();
        });

        // Most applied jobs
        $mostAppliedJobs = Cache::remember("admin_most_applied_jobs_{$range}", 600, function () use ($dateLimit) {
            return JobVacancy::with(['company' => function($q) { $q->withTrashed(); }])
                ->withCount(['jobApplications as totalCount' => function($q) use ($dateLimit) {
                    if ($dateLimit) $q->where('created_at', '>=', $dateLimit);
                }])
                ->whereNull('deleted_at')
                ->limit(5)
                ->orderByDesc('totalCount')
                ->get();
        });

        // Conversion rates
        $conversionRates = Cache::remember("admin_conversion_rates_{$range}", 600, function () use ($dateLimit) {
            return JobVacancy::with(['company' => function($q) { $q->withTrashed(); }])
                ->withCount(['jobApplications as totalCount' => function($q) use ($dateLimit) {
                    if ($dateLimit) $q->where('created_at', '>=', $dateLimit);
                }])
                ->having('totalCount', '>', 0)
                ->limit(5)
                ->orderByDesc('totalCount')
                ->get()
                ->map(function ($job) {
                    // Use max(viewCount, totalCount) as denominator to handle cases where
                    // applicants submit directly (via API/external link) without visiting the page,
                    // which would otherwise cause applications > views and rate > 100%.
                    $denominator = max((int) $job->viewCount, (int) $job->totalCount);
                    if ($denominator > 0) {
                        $job->conversionRate = round($job->totalCount / $denominator * 100, 2);
                    } else {
                        $job->conversionRate = 0;
                    }
                    // Expose raw view count so the view can show actual page views separately
                    $job->rawViewCount = (int) $job->viewCount;
                    return $job;
                });
        });

        // Chart 1: Applications Over Time (Line Chart)
        $applicationsOverTime = Cache::remember("admin_applications_over_time_{$range}", 600, function () use ($dateLimit) {
            $query = JobApplication::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereNull('deleted_at');
            if ($dateLimit) {
                $query->where('created_at', '>=', $dateLimit);
            } else {
                $query->where('created_at', '>=', now()->subDays(6)->startOfDay());
            }
            return $query->groupBy('date')->orderBy('date')->get();
        });

        // Chart 2: Application Status Distribution
        $applicationStatuses = Cache::remember("admin_application_statuses_{$range}", 600, function () use ($dateLimit) {
            $query = JobApplication::selectRaw('status, COUNT(*) as count')
                ->whereNull('deleted_at');
            if ($dateLimit) {
                $query->where('created_at', '>=', $dateLimit);
            }
            return $query->groupBy('status')->get();
        });

        // Actionable Alerts
        $actionableAlerts = [];
        
        $unreviewedApplicationsCount = Cache::remember("admin_unreviewed_applications", 600, function () {
            return JobApplication::where('status', \App\Enums\ApplicationStatus::PENDING)
                ->where('created_at', '<=', now()->subDays(7))
                ->whereNull('deleted_at')
                ->count();
        });

        if ($unreviewedApplicationsCount > 0) {
            $actionableAlerts[] = [
                'type' => 'warning',
                'message' => __('app.dashboard.unreviewed_applications_admin', ['count' => $unreviewedApplicationsCount])
            ];
        }

        $emptyCompaniesCount = Cache::remember("admin_empty_companies", 600, function () {
            return \App\Models\Company::doesntHave('jobVacancies')
                ->whereNull('deleted_at')
                ->count();
        });

        if ($emptyCompaniesCount > 0) {
            $actionableAlerts[] = [
                'type' => 'info',
                'message' => __('app.dashboard.empty_companies_admin', ['count' => $emptyCompaniesCount])
            ];
        }

        return [
            'activeUsers' => $activeUsers,
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications,
            'totalCompanies' => $totalCompanies,
            'avgAiScore' => $avgAiScore,
            'closedJobs' => $closedJobs,
            'mostAppliedJobs' => $mostAppliedJobs,
            'conversionRates' => $conversionRates,
            'applicationsOverTime' => $applicationsOverTime,
            'applicationStatuses' => $applicationStatuses,
            'rangeLabel' => $rangeLabel
        ];
    }

    private function companyOwnerDashboard($range)
    {
        $company = auth()->user()->company;
        $dateLimit = $this->getDateLimit($range);
        $rangeLabel = $this->getRangeLabel($range);

        if (! $company) {
            return [
                'activeUsers' => 0,
                'totalJobs' => 0,
                'totalApplications' => 0,
                'totalCompanies' => 0,
                'avgAiScore' => 0,
                'closedJobs' => 0,
                'mostAppliedJobs' => collect(),
                'conversionRates' => collect(),
                'applicationsOverTime' => collect(),
                'applicationStatuses' => collect(),
                'actionableAlerts' => [],
                'rangeLabel' => $rangeLabel
            ];
        }

        // Active users (job_seeker role)
        $activeUsers = Cache::remember("company_{$company->id}_active_users_{$range}", 600, function () use ($company, $dateLimit) {
            $query = User::where('role', 'job_seeker')
                ->whereHas('jobApplications', function($q) use ($company) {
                    $q->whereIn('jobVacancyId', $company->jobVacancies->pluck('id'));
                });
            if ($dateLimit) $query->where('last_login_at', '>=', $dateLimit);
            else $query->where('last_login_at', '>=', now()->subDays(30));
            return $query->count();
        });

        // Total jobs
        $totalJobs = Cache::remember("company_{$company->id}_total_jobs_{$range}", 600, function () use ($company, $dateLimit) {
            $query = JobVacancy::whereIn('id', $company->jobVacancies->pluck('id'))
                ->whereNull('deleted_at');
            if ($dateLimit) $query->where('created_at', '>=', $dateLimit);
            return $query->count();
        });

        // Total applications
        $totalApplications = Cache::remember("company_{$company->id}_total_applications_{$range}", 600, function () use ($company, $dateLimit) {
            $query = JobApplication::whereIn('jobVacancyId', $company->jobVacancies->pluck('id'))
                ->whereNull('deleted_at');
            if ($dateLimit) $query->where('created_at', '>=', $dateLimit);
            return $query->count();
        });
        
        // Total Views
        $totalCompanies = Cache::remember("company_{$company->id}_total_views_{$range}", 600, function () use ($company, $dateLimit) {
            $query = JobVacancy::whereIn('id', $company->jobVacancies->pluck('id'))->whereNull('deleted_at');
            if ($dateLimit) $query->where('created_at', '>=', $dateLimit);
            return (int) $query->sum('viewCount');
        });

        // Average AI Match Score
        $avgAiScore = Cache::remember("company_{$company->id}_avg_ai_score_{$range}", 600, function () use ($company, $dateLimit) {
            $query = JobApplication::whereIn('jobVacancyId', $company->jobVacancies->pluck('id'))
                                   ->whereNotNull('aiGeneratedScore')
                                   ->whereNull('deleted_at');
            if ($dateLimit) $query->where('created_at', '>=', $dateLimit);
            return round((float) $query->avg('aiGeneratedScore'), 1);
        });

        // Closed Jobs
        $closedJobs = Cache::remember("company_{$company->id}_closed_jobs_{$range}", 600, function () use ($company, $dateLimit) {
            $query = JobVacancy::onlyTrashed()->where('companyId', $company->id);
            if ($dateLimit) $query->where('deleted_at', '>=', $dateLimit);
            return $query->count();
        });
        
        // Most applied jobs
        $mostAppliedJobs = Cache::remember("company_{$company->id}_most_applied_jobs_{$range}", 600, function () use ($company, $dateLimit) {
            return JobVacancy::with(['company' => function($q) { $q->withTrashed(); }])
                ->withCount(['jobApplications as totalCount' => function($q) use ($dateLimit) {
                    if ($dateLimit) $q->where('created_at', '>=', $dateLimit);
                }])
                ->whereIn('id', $company->jobVacancies->pluck('id'))
                ->limit(5)
                ->orderByDesc('totalCount')
                ->get();
        });

        // Conversion rates
        $conversionRates = Cache::remember("company_{$company->id}_conversion_rates_{$range}", 600, function () use ($company, $dateLimit) {
            return JobVacancy::with(['company' => function($q) { $q->withTrashed(); }])
                ->withCount(['jobApplications as totalCount' => function($q) use ($dateLimit) {
                    if ($dateLimit) $q->where('created_at', '>=', $dateLimit);
                }])
                ->whereIn('id', $company->jobVacancies->pluck('id'))
                ->having('totalCount', '>', 0)
                ->limit(5)
                ->orderByDesc('totalCount')
                ->get()
                ->map(function ($job) {
                    // Use max(viewCount, totalCount) as denominator to handle cases where
                    // applicants submit directly (via API/external link) without visiting the page,
                    // which would otherwise cause applications > views and rate > 100%.
                    $denominator = max((int) $job->viewCount, (int) $job->totalCount);
                    if ($denominator > 0) {
                        $job->conversionRate = round($job->totalCount / $denominator * 100, 2);
                    } else {
                        $job->conversionRate = 0;
                    }
                    // Expose raw view count so the view can show actual page views separately
                    $job->rawViewCount = (int) $job->viewCount;
                    return $job;
                });
        });

        // Chart 1: Applications Over Time
        $applicationsOverTime = Cache::remember("company_{$company->id}_applications_over_time_{$range}", 600, function () use ($company, $dateLimit) {
            $query = JobApplication::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereIn('jobVacancyId', $company->jobVacancies->pluck('id'))
                ->whereNull('deleted_at');
            if ($dateLimit) {
                $query->where('created_at', '>=', $dateLimit);
            } else {
                $query->where('created_at', '>=', now()->subDays(6)->startOfDay());
            }
            return $query->groupBy('date')->orderBy('date')->get();
        });

        // Chart 2: Application Status Distribution
        $applicationStatuses = Cache::remember("company_{$company->id}_application_statuses_{$range}", 600, function () use ($company, $dateLimit) {
            $query = JobApplication::selectRaw('status, COUNT(*) as count')
                ->whereIn('jobVacancyId', $company->jobVacancies->pluck('id'))
                ->whereNull('deleted_at');
            if ($dateLimit) {
                $query->where('created_at', '>=', $dateLimit);
            }
            return $query->groupBy('status')->get();
        });

        // Actionable Alerts
        $actionableAlerts = [];

        $pendingApplicationsCount = Cache::remember("company_{$company->id}_pending_applications", 600, function () use ($company) {
            return JobApplication::whereIn('jobVacancyId', $company->jobVacancies->pluck('id'))
                ->where('status', \App\Enums\ApplicationStatus::PENDING)
                ->whereNull('deleted_at')
                ->count();
        });

        if ($pendingApplicationsCount > 0) {
            $actionableAlerts[] = [
                'type' => 'warning',
                'message' => __('app.dashboard.pending_applications_company', ['count' => $pendingApplicationsCount])
            ];
        }

        $jobsWithoutApplicationsCount = Cache::remember("company_{$company->id}_empty_jobs", 600, function () use ($company) {
            return JobVacancy::where('companyId', $company->id)
                ->doesntHave('jobApplications')
                ->whereNull('deleted_at')
                ->count();
        });

        if ($jobsWithoutApplicationsCount > 0) {
            $actionableAlerts[] = [
                'type' => 'info',
                'message' => __('app.dashboard.empty_jobs_company', ['count' => $jobsWithoutApplicationsCount])
            ];
        }

        return [
            'activeUsers' => $activeUsers,
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications,
            'totalCompanies' => $totalCompanies,
            'avgAiScore' => $avgAiScore,
            'closedJobs' => $closedJobs,
            'mostAppliedJobs' => $mostAppliedJobs,
            'conversionRates' => $conversionRates,
            'applicationsOverTime' => $applicationsOverTime,
            'applicationStatuses' => $applicationStatuses,
            'actionableAlerts' => $actionableAlerts,
            'rangeLabel' => $rangeLabel
        ];
    }
}