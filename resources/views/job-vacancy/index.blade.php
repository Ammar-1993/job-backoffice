<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.jobs.title') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <x-toast-notification />

            <!-- Floating Toolbar -->
            <div class="flex flex-col md:flex-row justify-between items-center bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 gap-5">
                
                <!-- Tabs -->
                <div class="flex space-x-1 bg-gray-50 p-1.5 rounded-xl border border-gray-100">
                    <a href="{{ route('job-vacancies.index') }}" 
                       class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 {{ !request('archived') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-indigo-600 hover:bg-white/50' }}">
                        {{ __('app.jobs.active_jobs') }}
                    </a>
                    <a href="{{ route('job-vacancies.index', ['archived' => 'true']) }}" 
                       class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 {{ request('archived') == 'true' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-indigo-600 hover:bg-white/50' }}">
                        {{ __('app.jobs.archived_jobs') }}
                    </a>
                </div>

                <!-- Search & Add -->
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                    <form method="GET" action="{{ route('job-vacancies.index') }}" class="relative w-full sm:w-72">
                        @if(request('archived'))
                            <input type="hidden" name="archived" value="true">
                        @endif
                         <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-indigo-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="pl-10 block w-full rounded-xl border-gray-200 bg-gray-50 hover:bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 transition-colors" 
                               placeholder="{{ __('app.jobs.search_placeholder') }}...">
                    </form>

                    <a href="{{ route('job-vacancies.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 border border-transparent rounded-xl font-bold text-sm text-white shadow-md hover:shadow-lg hover:from-indigo-500 hover:to-indigo-600 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 active:scale-95 w-full sm:w-auto justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        {{ __('app.jobs.add_job') }}
                    </a>
                </div>
            </div>

            <!-- Job Vacancy Table -->
            <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-gray-100">
                <!-- Desktop Table (hidden on mobile) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.jobs.details') }}</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.jobs.location_type') }}</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.jobs.salary') }}</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($jobVacancies as $jobVacancy)
                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center">
                                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-700 font-extrabold text-xl mr-4 flex-shrink-0 border border-indigo-50 shadow-sm group-hover:scale-105 transition-transform">
                                                {{ mb_strtoupper(mb_substr($jobVacancy->title, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 group-hover:text-indigo-700 transition-colors">
                                                    @if(request('archived') != 'true')
                                                        <a href="{{ route('job-vacancies.show', $jobVacancy->id) }}" class="hover:underline">{{ $jobVacancy->title }}</a>
                                                    @else
                                                        <span>{{ $jobVacancy->title }}</span>
                                                    @endif
                                                </div>
                                                @if(auth()->user()->role == 'admin')
                                                    <div class="flex items-center text-sm font-medium text-gray-500 mt-1">
                                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                        {{ $jobVacancy->company?->name ?? __('app.dashboard.company_deleted') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center text-sm font-semibold text-gray-700">
                                                <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                {{ $jobVacancy->location }}
                                            </div>
                                            <div>
                                                @php
                                                    $typeStyles = [
                                                        'Full-Time' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                        'Contract' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                        'Remote' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                        'Hybrid' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                    ];
                                                    $typeValue = $jobVacancy->type instanceof \BackedEnum ? $jobVacancy->type->value : $jobVacancy->type;
                                                    $style = $typeStyles[$typeValue] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                                @endphp
                                                <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-lg border {{ $style }}">
                                                    {{ $typeValue }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right">
                                        <div class="inline-flex items-center px-3 py-1.5 bg-green-50 border border-green-100 text-green-700 rounded-xl text-sm font-black shadow-sm">
                                            <svg class="w-4 h-4 mr-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            ${{ number_format($jobVacancy->salary, 0) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-2">
                                            @if(request('archived') == 'true')
                                                <form action="{{ route('job-vacancies.restore', $jobVacancy->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg transition-colors border border-emerald-100 font-semibold shadow-sm">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                        {{ __('app.common.restore') }}
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('job-vacancies.edit', $jobVacancy->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors border border-amber-100" title="{{ __('app.common.edit') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <x-confirm-popover action="{{ route('job-vacancies.destroy', $jobVacancy->id) }}" question="{{ __('app.jobs.confirm_archive') }}">
                                                    <button type="button" class="inline-flex items-center justify-center w-8 h-8 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg transition-colors border border-rose-100 ml-2" title="{{ __('app.common.archive') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </x-confirm-popover>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                    
                    @if($jobVacancies->isEmpty())
                        <div class="py-12">
                            <x-empty-state
                                icon="briefcase"
                                title="{{ __('app.empty_states.jobs.title') }}"
                                description="{{ __('app.empty_states.jobs.description') }}"
                                actionText="{{ __('app.empty_states.jobs.action') }}"
                                actionUrl="{{ route('job-vacancies.create') }}"
                            />
                        </div>
                    @endif
                </div>
                
                <!-- Mobile Cards (hidden on desktop) -->
                <div class="md:hidden bg-gray-50/50 p-4 space-y-4">
                    @forelse($jobVacancies as $jobVacancy)
                        <div class="bg-white p-5 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 transition-all duration-300">
                            <div class="space-y-4">
                                <!-- Title and Company -->
                                <div>
                                    <h3 class="font-bold text-base text-gray-900 leading-tight">
                                        @if(request('archived') != 'true')
                                            <a href="{{ route('job-vacancies.show', $jobVacancy->id) }}" class="text-indigo-600 hover:underline">
                                                {{ $jobVacancy->title }}
                                            </a>
                                        @else
                                            {{ $jobVacancy->title }}
                                        @endif
                                    </h3>
                                    @if(auth()->user()->role == 'admin')
                                        <p class="text-sm font-semibold text-gray-500 mt-1.5 flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $jobVacancy->company?->name ?? __('app.dashboard.company_deleted') }}
                                        </p>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-50">
                                    <!-- Location -->
                                    <div class="flex items-center text-sm font-semibold text-gray-700">
                                        <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ mb_strimwidth($jobVacancy->location, 0, 15, "...") }}
                                    </div>
                                    <!-- Type -->
                                    <div class="flex justify-end">
                                        @php
                                            $typeStylesMobile = [
                                                'Full-Time' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'Contract' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Remote' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'Hybrid' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            ];
                                            $typeValueMobile = $jobVacancy->type instanceof \BackedEnum ? $jobVacancy->type->value : $jobVacancy->type;
                                            $styleMobile = $typeStylesMobile[$typeValueMobile] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                        @endphp
                                        <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-lg border {{ $styleMobile }}">
                                            {{ $typeValueMobile }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Salary -->
                                <div class="pt-3 border-t border-gray-50">
                                    <div class="inline-flex items-center px-3 py-1.5 bg-green-50 border border-green-100 text-green-700 rounded-xl text-sm font-black shadow-sm">
                                        <svg class="w-4 h-4 mr-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        ${{ number_format($jobVacancy->salary, 0) }}
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                @if(request('archived') == 'true')
                                    <form action="{{ route('job-vacancies.restore', $jobVacancy->id) }}" method="POST" class="pt-3">
                                        @csrf @method('PUT')
                                        <button type="submit" class="w-full px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 text-sm font-bold hover:bg-emerald-100 transition-colors shadow-sm flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            {{ __('app.common.restore') }}
                                        </button>
                                    </form>
                                @else
                                    <div class="flex space-x-2 pt-3">
                                        <a href="{{ route('job-vacancies.edit', $jobVacancy->id) }}" class="flex-1 flex items-center justify-center px-4 py-2 bg-amber-50 border border-amber-100 text-amber-600 rounded-xl text-sm font-bold hover:bg-amber-100 transition-colors shadow-sm">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            {{ __('app.common.edit') }}
                                        </a>
                                        <x-confirm-popover action="{{ route('job-vacancies.destroy', $jobVacancy->id) }}" question="{{ __('app.jobs.confirm_archive') }}">
                                            <button type="button" class="w-full flex items-center justify-center px-4 py-2 bg-rose-50 border border-rose-100 text-rose-600 rounded-xl text-sm font-bold hover:bg-rose-100 transition-colors shadow-sm">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                {{ __('app.common.archive') }}
                                            </button>
                                        </x-confirm-popover>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-8">
                            <x-empty-state
                                icon="briefcase"
                                title="{{ __('app.empty_states.jobs.title') }}"
                                description="{{ __('app.empty_states.jobs.description') }}"
                                actionText="{{ __('app.empty_states.jobs.action') }}"
                                actionUrl="{{ route('job-vacancies.create') }}"
                            />
                        </div>
                    @endforelse
                </div>

                @if($jobVacancies->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $jobVacancies->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>