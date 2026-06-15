<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $company->name }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />

        @if (auth()->user()->role == 'admin')
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('companies.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 border border-gray-200 rounded-xl shadow-sm transition-all text-sm font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    {{ __('app.common.back') }}
                </a>
            </div>
        @endif

        <!-- Profile Header Card -->
        <div class="w-full mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
            
            <div class="px-8 pb-8 relative">
                <!-- Avatar & Actions row -->
                <div class="flex justify-between items-end -mt-12 mb-6">
                    <div class="h-24 w-24 rounded-2xl bg-white p-1.5 shadow-md">
                        <div class="h-full w-full rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-4xl border border-indigo-100">
                            {{ strtoupper(substr($company->name, 0, 1)) }}
                        </div>
                    </div>
                    
                    <div class="flex space-x-3 items-center">
                        @if (auth()->user()->role == 'company_owner')
                            <a href="{{ route('my-company.edit') }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl hover:bg-indigo-100 font-semibold text-sm transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                {{ __('app.common.edit') }}
                            </a>
                        @else
                            <a href="{{ route('companies.edit', ['company' => $company->id, 'redirectToList' => 'false']) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl hover:bg-indigo-100 font-semibold text-sm transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                {{ __('app.common.edit') }}
                            </a>
                        @endif

                        @if (auth()->user()->role == 'admin')
                            <x-confirm-popover action="{{ route('companies.destroy', $company->id) }}" question="{{ __('app.companies.confirm_archive') }}">
                                <button type="button" class="inline-flex items-center px-4 py-2 bg-rose-50 text-rose-700 rounded-xl hover:bg-rose-100 font-semibold text-sm transition-colors shadow-sm ml-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                    {{ __('app.common.archive') }}
                                </button>
                            </x-confirm-popover>
                        @endif
                    </div>
                </div>

                <!-- Company Info -->
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900">{{ $company->name }}</h1>
                    <p class="text-indigo-600 font-medium mt-1">{{ $company->industry }}</p>
                </div>

                <!-- Info Grid -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pt-6 border-t border-gray-100">
                    <div class="flex items-start space-x-3">
                        <div class="p-2 bg-gray-50 rounded-lg text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('app.companies.owner_label') }}</p>
                            <p class="text-sm text-gray-900 font-medium">{{ optional($company->owner)->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="p-2 bg-gray-50 rounded-lg text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('app.companies.email_label') }}</p>
                            @if(optional($company->owner)->email)
                                <a href="mailto:{{ optional($company->owner)->email }}" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline font-medium break-all">{{ optional($company->owner)->email }}</a>
                            @else
                                <p class="text-sm text-gray-900 font-medium">N/A</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="p-2 bg-gray-50 rounded-lg text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('app.companies.address_label') }}</p>
                            <p class="text-sm text-gray-900 font-medium">{{ $company->address }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="p-2 bg-gray-50 rounded-lg text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ __('app.companies.website_label') }}</p>
                            <a href="{{ $company->website }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline font-medium break-all">{{ $company->website }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (auth()->user()->role == 'admin')
            <!-- Tabs Navigation -->
            <div class="mb-6 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="{{ route('companies.show', ['company' => $company->id, 'tab' => 'jobs']) }}"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm transition-colors {{ request('tab') == 'jobs' || request('tab') == '' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        {{ __('app.companies.jobs_tab') }}
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">{{ $company->jobVacancies->count() }}</span>
                    </a>
                    
                    <a href="{{ route('companies.show', ['company' => $company->id, 'tab' => 'applications']) }}"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-semibold text-sm transition-colors {{ request('tab') == 'applications' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        {{ __('app.companies.applications_tab') }}
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">{{ $company->jobApplications->count() }}</span>
                    </a>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Jobs Tab -->
                <div id="jobs" class="{{ request('tab') == 'jobs' || request('tab') == '' ? 'block' : 'hidden' }}">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.jobs.form_title') }}</th>
                                    <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.jobs.form_type') }}</th>
                                    <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.jobs.form_location') }}</th>
                                    <th class="py-3 px-6 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($company->jobVacancies as $job)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-4 px-6 whitespace-nowrap text-sm font-bold text-gray-900">{{ $job->title }}</td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $job->type }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-500 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $job->location }}
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('job-vacancies.show', $job->id) }}"
                                                class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                                                {{ __('app.common.view') }} <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500 text-sm">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                {{ __('app.jobs.no_jobs') ?? 'No jobs found.' }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Applications Tab -->
                <div id="applications" class="{{ request('tab') == 'applications' ? 'block' : 'hidden' }}">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.applications.applicant_name') }}</th>
                                    <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.dashboard.job_title') }}</th>
                                    <th class="py-3 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.applications.status') }}</th>
                                    <th class="py-3 px-6 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('app.common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($company->jobApplications as $application)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-4 px-6 whitespace-nowrap text-sm font-bold text-gray-900 flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold mr-3 border border-emerald-200">
                                                {{ strtoupper(substr(optional($application->user)->name ?? 'N', 0, 1)) }}
                                            </div>
                                            {{ optional($application->user)->name ?? 'N/A' }}
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-600 font-medium">{{ optional($application->jobVacancy)->title ?? 'N/A' }}</td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                {{ $application->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('job-applications.show', $application->id) }}"
                                                class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                                                {{ __('app.common.view') }} <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500 text-sm">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                {{ __('app.jobs.no_applications') }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

</x-app-layout>