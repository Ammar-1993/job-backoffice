<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $jobVacancy->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-toast-notification />

            <!-- Back & Actions Top Bar -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                <a href="{{ route('job-vacancies.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 hover:text-indigo-600 shadow-sm transition-all duration-300 group">
                    <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    {{ __('app.common.back') }}
                </a>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('job-vacancies.edit', ['job_vacancy' => $jobVacancy->id, 'redirectToList' => 'false']) }}"
                        class="inline-flex items-center px-4 py-2 bg-white border border-indigo-100 rounded-xl text-sm font-bold text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 shadow-sm transition-all duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        {{ __('app.common.edit') }}
                    </a>
                    <form action="{{ route('job-vacancies.destroy', $jobVacancy->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('app.common.confirm_delete') ?? 'Are you sure you want to delete this job?' }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-white border border-rose-100 rounded-xl text-sm font-bold text-rose-600 hover:bg-rose-50 hover:border-rose-200 shadow-sm transition-all duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            {{ __('app.common.archive') ?? 'Archive' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Profile Header Card -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8 relative">
                <!-- Cover Gradient -->
                <div class="h-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600 relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                    <div class="absolute -bottom-16 -right-16 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
                </div>
                
                <div class="px-8 pb-8 pt-0 relative z-10">
                    <div class="flex flex-col md:flex-row md:items-end justify-between">
                        <div class="flex items-end -mt-12 mb-4 md:mb-0">
                            <div class="w-24 h-24 bg-white rounded-2xl shadow-md border-4 border-white flex items-center justify-center overflow-hidden">
                                @if(optional($jobVacancy->company)->logo)
                                    <img src="{{ asset('storage/' . $jobVacancy->company->logo) }}" alt="{{ $jobVacancy->company->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-indigo-50 text-indigo-500 flex items-center justify-center">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-5 pb-1">
                                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $jobVacancy->title }}</h1>
                                <p class="text-base text-gray-500 font-medium mt-1">{{ optional($jobVacancy->company)->name ?? 'Company Not Specified' }} • {{ optional($jobVacancy->jobCategory)->name ?? 'Uncategorized' }}</p>
                            </div>
                        </div>
                        
                        <!-- Badges -->
                        <div class="flex flex-wrap gap-2 md:pb-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                {{ $jobVacancy->type }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-50 text-green-700 border border-green-100">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                ${{ number_format($jobVacancy->salary, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Details -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- About Job Card -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-extrabold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ __('app.jobs.info_title') ?? 'Job Overview' }}
                            </h3>
                        </div>
                        <div class="p-6 space-y-5">
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('app.jobs.form_location') }}</h4>
                                <div class="mt-1 flex items-center text-gray-900 font-medium">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $jobVacancy->location }}
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('app.jobs.form_company') }}</h4>
                                <div class="mt-1 flex items-center text-gray-900 font-medium">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ optional($jobVacancy->company)->name ?? 'N/A' }}
                                </div>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('app.common.created_at') ?? 'Posted On' }}</h4>
                                <div class="mt-1 flex items-center text-gray-900 font-medium">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $jobVacancy->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Card -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-lg font-extrabold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                {{ __('app.jobs.form_description') }}
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="prose prose-sm prose-indigo text-gray-600">
                                @if($jobVacancy->description)
                                    <p class="whitespace-pre-line leading-relaxed">{{ $jobVacancy->description }}</p>
                                @else
                                    <p class="italic text-gray-400">No description provided.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Applications Tab -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        
                        <!-- Header & Tabs -->
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <h3 class="text-lg font-extrabold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                {{ __('app.jobs.applications_tab') }}
                                <span class="ml-3 inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                    {{ $jobVacancy->jobApplications->count() }}
                                </span>
                            </h3>
                        </div>

                        <!-- Applications List -->
                        <div class="p-0">
                            @if($jobVacancy->jobApplications->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-white">
                                            <tr>
                                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.applications.applicant_name') }}</th>
                                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.applications.status') }}</th>
                                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.common.created_at') ?? 'Applied On' }}</th>
                                                <th scope="col" class="relative px-6 py-4 text-right">
                                                    <span class="sr-only">{{ __('app.common.actions') }}</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach ($jobVacancy->jobApplications as $application)
                                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10">
                                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200">
                                                                    {{ strtoupper(substr(optional($application->user)->name ?? 'U', 0, 1)) }}
                                                                </div>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                                                    {{ optional($application->user)->name ?? 'Unknown Applicant' }}
                                                                </div>
                                                                <div class="text-sm text-gray-500 font-medium">
                                                                    {{ optional($application->user)->email ?? 'No email' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @php
                                                            $statusValue = $application->status instanceof \UnitEnum ? $application->status->value : $application->status;
                                                            $statusColors = [
                                                                'Pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                                'Reviewed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                                'Accepted' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                                'Rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                            ];
                                                            $colorClass = $statusColors[$statusValue] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                                        @endphp
                                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $colorClass }}">
                                                            {{ $statusValue }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                                        {{ $application->created_at->format('M d, Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('job-applications.show', $application->id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                                                            {{ __('app.common.view') ?? 'View' }}
                                                            <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-12 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900">{{ __('app.jobs.no_applications') }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">There are no applications for this job yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>