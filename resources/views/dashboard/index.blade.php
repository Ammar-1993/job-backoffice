<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('app.dashboard.title') }}
            </h2>
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-2 rtl:space-x-reverse" id="filter-form" onsubmit="event.preventDefault(); updateDashboard(document.getElementById('range').value);">
                <label for="range" class="text-sm font-medium text-gray-700">{{ __('app.dashboard.filter_by') ?? 'Filter:' }}</label>
                <select name="range" id="range" onchange="updateDashboard(this.value)" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm text-sm">
                    <option value="today" {{ $range == 'today' ? 'selected' : '' }}>{{ __('app.dashboard.today') ?? 'Today' }}</option>
                    <option value="this_week" {{ $range == 'this_week' ? 'selected' : '' }}>{{ __('app.dashboard.this_week') ?? 'This Week' }}</option>
                    <option value="this_month" {{ $range == 'this_month' ? 'selected' : '' }}>{{ __('app.dashboard.this_month') ?? 'This Month' }}</option>
                    <option value="this_year" {{ $range == 'this_year' ? 'selected' : '' }}>{{ __('app.dashboard.this_year') ?? 'This Year' }}</option>
                    <option value="all_time" {{ $range == 'all_time' ? 'selected' : '' }}>{{ __('app.dashboard.all_time') ?? 'All Time' }}</option>
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-6 flex flex-col gap-6" id="dashboard-content" style="transition: opacity 0.3s ease;">
        
        <!-- Needs Attention Alerts -->
        @if(isset($analytics['actionableAlerts']) && count($analytics['actionableAlerts']) > 0)
            <div class="bg-amber-50 border-l-4 border-amber-400 p-5 rounded-r-xl shadow-sm">
                <div class="flex items-center mb-3">
                    <svg class="w-6 h-6 text-amber-500 mr-2 rtl:ml-2 rtl:mr-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="text-lg font-bold text-amber-800">{{ __('app.dashboard.needs_attention') }}</h3>
                </div>
                <ul class="list-disc list-inside text-sm text-amber-700 space-y-1 font-medium">
                    @foreach($analytics['actionableAlerts'] as $alert)
                        <li>{{ $alert['message'] }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Metric Card 1: Active Users --}}
            <x-metric-card 
                title="{{ __('app.dashboard.active_users') }}" 
                :value="$analytics['activeUsers']" 
                subtitle="{{ $analytics['rangeLabel'] }}" 
                color="primary-600"
                icon="Users" 
                href="{{ route('users.index') }}" />
            
            {{-- Metric Card 2: Total Companies / Total Views --}}
            @if(auth()->user()->role == 'admin')
                <x-metric-card 
                    title="{{ __('app.dashboard.total_companies') ?? 'Total Companies' }}" 
                    :value="$analytics['totalCompanies']" 
                    subtitle="{{ $analytics['rangeLabel'] }}" 
                    color="indigo-600"
                    icon="Building" 
                    href="{{ route('companies.index') }}" />
            @else
                <x-metric-card 
                    title="{{ __('app.dashboard.total_views') ?? 'Total Job Views' }}" 
                    :value="$analytics['totalCompanies']" 
                    subtitle="{{ $analytics['rangeLabel'] }}" 
                    color="indigo-600"
                    icon="Eye" 
                    href="{{ route('job-vacancies.index') }}" />
            @endif
            
            {{-- Metric Card 3: Active Jobs --}}
            <x-metric-card 
                title="{{ __('app.dashboard.active_jobs') ?? 'Active Jobs' }}" 
                :value="$analytics['totalJobs']" 
                subtitle="{{ $analytics['rangeLabel'] }}" 
                color="secondary-600"
                icon="Briefcase" 
                href="{{ route('job-vacancies.index') }}" />
                
            {{-- Metric Card 4: Closed Jobs --}}
            <x-metric-card 
                title="{{ __('app.dashboard.closed_jobs') ?? 'Closed Jobs' }}" 
                :value="$analytics['closedJobs']" 
                subtitle="{{ $analytics['rangeLabel'] }}" 
                color="gray-600"
                icon="Archive" 
                href="{{ route('job-vacancies.index') }}" />
            
            {{-- Metric Card 5: Total Applications --}}
            <x-metric-card 
                title="{{ __('app.dashboard.total_applications') }}" 
                :value="$analytics['totalApplications']" 
                subtitle="{{ $analytics['rangeLabel'] }}" 
                color="primary-700"
                icon="FileText" 
                href="{{ route('job-applications.index') }}" />
                
            {{-- Metric Card 6: Average AI Match Score --}}
            <x-metric-card 
                title="{{ __('app.dashboard.avg_ai_score') ?? 'Avg AI Match Score' }}" 
                :value="$analytics['avgAiScore'] . '%'" 
                subtitle="{{ $analytics['rangeLabel'] }}" 
                color="green-600"
                icon="Target" 
                href="{{ route('job-applications.index') }}" />
        </div>

        <!-- Most Applied Jobs -->
        <div class="p-8 bg-white overflow-hidden shadow-[0_4px_24px_rgba(0,0,0,0.06)] rounded-xl border border-gray-50">
            <h3 class="text-xl font-bold text-gray-900 flex items-center space-x-3 rtl:space-x-reverse">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary-600"><polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline><polyline points="16 17 22 17 22 11"></polyline></svg>
                <span>{{ __('app.dashboard.most_applied_jobs') }}</span>
            </h3>
            
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="text-left border-b border-gray-100">
                            <th class="pb-4 text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('app.dashboard.job_title') }}</th>
                            @if(auth()->user()->role == 'admin')
                                <th class="pb-4 text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('app.dashboard.company') }}</th>
                            @endif
                            <th class="pb-4 text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('app.dashboard.total_applications') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($analytics['mostAppliedJobs'] as $job)
                            <tr>
                                <td class="py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    <a href="{{ route('job-vacancies.show', $job->id) }}" class="text-primary-600 hover:text-primary-800 transition-colors">
                                        {{ $job->title }}
                                    </a>
                                </td>
                                @if(auth()->user()->role == 'admin')
                                    <td class="py-4 whitespace-nowrap text-sm text-gray-600">
                                        @if($job->company)
                                            <a href="{{ route('companies.show', $job->company->id) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors font-medium">
                                                {{ $job->company->name }}
                                            </a>
                                        @else
                                            <span class="text-xs text-red-500">{{ __('app.dashboard.company_deleted') }}</span>
                                        @endif
                                    </td>
                                @endif
                                <td class="py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $job->totalCount }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Conversion Rates -->
        <div class="p-8 bg-white overflow-hidden shadow-[0_4px_24px_rgba(0,0,0,0.06)] rounded-xl border border-gray-50">
            <h3 class="text-xl font-bold text-gray-900 flex items-center space-x-3 rtl:space-x-reverse">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary-600"><line x1="12" x2="12" y1="20" y2="10"></line><line x1="18" x2="18" y1="20" y2="4"></line><line x1="6" x2="6" y1="20" y2="16"></line></svg>
                <span>{{ __('app.dashboard.conversion_rates') }}</span>
            </h3>
            
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="text-left border-b border-gray-100">
                            <th class="pb-4 text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('app.dashboard.job_title') }}</th>
                            <th class="pb-4 text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('app.dashboard.views') }}</th>
                            <th class="pb-4 text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('app.dashboard.applications') }}</th>
                            <th class="pb-4 text-xs font-bold text-gray-600 uppercase tracking-wider">{{ __('app.dashboard.conversion_rate') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($analytics['conversionRates'] as $conversionRate)
                            <tr>
                                <td class="py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    <a href="{{ route('job-vacancies.show', $conversionRate->id) }}" class="text-primary-600 hover:text-primary-800 transition-colors">
                                        {{ $conversionRate->title }}
                                    </a>
                                </td>
                                <td class="py-4 whitespace-nowrap text-sm text-gray-600">{{ $conversionRate->viewCount }}</td>
                                <td class="py-4 whitespace-nowrap text-sm text-gray-600">{{ $conversionRate->totalCount }}</td>
                                <td class="py-4 whitespace-nowrap text-sm font-medium">
                                    @php
                                        $v = (float) $conversionRate->conversionRate;
                                        if ($v >= 50) {
                                            $barColor = 'bg-green-500';
                                            $textColor = 'text-green-700';
                                        } elseif ($v >= 20) {
                                            $barColor = 'bg-yellow-500';
                                            $textColor = 'text-yellow-700';
                                        } else {
                                            $barColor = 'bg-red-500';
                                            $textColor = 'text-red-700';
                                        }
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <span class="w-12 text-right {{ $textColor }}">{{ $conversionRate->conversionRate }}%</span>
                                        <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $barColor }} transition-all duration-500" style="width: {{ $conversionRate->conversionRate }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Line Chart: Applications Over Time -->
            <div class="p-8 bg-white overflow-hidden shadow-[0_4px_24px_rgba(0,0,0,0.06)] rounded-xl border border-gray-50">
                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ __('app.dashboard.applications_over_time') ?? 'Applications Over Time' }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ $analytics['rangeLabel'] }}</p>
                <div id="applicationsChart"></div>
            </div>

            <!-- Donut Chart: Application Statuses -->
            <div class="p-8 bg-white overflow-hidden shadow-[0_4px_24px_rgba(0,0,0,0.06)] rounded-xl border border-gray-50">
                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ __('app.dashboard.application_statuses') ?? 'Application Statuses' }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ $analytics['rangeLabel'] }}</p>
                <div id="statusesChart"></div>
            </div>
        </div>
        <!-- Inline Script for Charts (Moved inside dashboard-content for AJAX re-evaluation) -->
        <script>
            // Render charts function that waits for ApexCharts to be loaded
            function renderDashboardCharts() {
                if (typeof ApexCharts === 'undefined') {
                    setTimeout(renderDashboardCharts, 50);
                    return;
                }

                const appChartEl = document.querySelector("#applicationsChart");
                const statChartEl = document.querySelector("#statusesChart");
                
                if (appChartEl && !appChartEl.hasChildNodes()) {
                    const timeData = @json($analytics['applicationsOverTime']);
                    const dates = timeData.map(item => item.date);
                    const counts = timeData.map(item => item.count);

                    const lineOptions = {
                        chart: { type: 'area', height: 350, toolbar: { show: false }, fontFamily: 'inherit' },
                        series: [{ name: 'Applications', data: counts }],
                        xaxis: { categories: dates, type: 'category' },
                        yaxis: { 
                            min: 0,
                            forceNiceScale: true,
                            labels: { formatter: function(val) { return Math.floor(val); } }
                        },
                        colors: ['#4f46e5'],
                        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.2, stops: [0, 90, 100] } },
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 3 }
                    };
                    
                    if(counts.length > 0) {
                        new ApexCharts(appChartEl, lineOptions).render();
                    } else {
                        appChartEl.innerHTML = '<div class="text-center text-gray-500 py-10">No data available yet</div>';
                    }
                }

                if (statChartEl && !statChartEl.hasChildNodes()) {
                    const statusData = @json($analytics['applicationStatuses']);
                    const labels = statusData.map(item => {
                        return item.status.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
                    });
                    const series = statusData.map(item => item.count);
                    
                    const colors = statusData.map(item => {
                        if(item.status === 'accepted') return '#10b981';
                        if(item.status === 'rejected') return '#ef4444';
                        return '#6b7280'; // pending
                    });

                    const donutOptions = {
                        chart: { type: 'donut', height: 350, fontFamily: 'inherit' },
                        series: series,
                        labels: labels,
                        colors: colors,
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '70%',
                                    labels: {
                                        show: true,
                                        name: { show: true },
                                        value: { show: true }
                                    }
                                }
                            }
                        },
                        dataLabels: { enabled: false },
                        legend: { 
                            position: 'bottom',
                            formatter: function(seriesName, opts) {
                                const val = opts.w.globals.series[opts.seriesIndex];
                                const total = opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                const percent = total > 0 ? Math.round((val / total) * 100) : 0;
                                return seriesName + ' — ' + val + ' (' + percent + '%)';
                            }
                        }
                    };
                    
                    if(series.length > 0) {
                        new ApexCharts(statChartEl, donutOptions).render();
                    } else {
                        statChartEl.innerHTML = '<div class="text-center text-gray-500 py-10">No data available yet</div>';
                    }
                }
            }

            // Execute immediately (it will wait if ApexCharts is not ready)
            renderDashboardCharts();
        </script>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        function updateDashboard(range) {
            const container = document.querySelector('#dashboard-content');
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';

            // Push state to update URL without reloading
            const url = new URL(window.location);
            url.searchParams.set('range', range);
            window.history.pushState({}, '', url);

            fetch('?range=' + range, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Find the new content
                const newContent = doc.querySelector('#dashboard-content');
                if (newContent) {
                    container.innerHTML = newContent.innerHTML;
                    
                    // Re-execute scripts within the new content to re-render charts
                    const scripts = container.querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        newScript.remove();
                    });
                }

                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            })
            .catch(err => {
                console.error('Failed to update dashboard:', err);
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            });
        }
    </script>
</x-app-layout>