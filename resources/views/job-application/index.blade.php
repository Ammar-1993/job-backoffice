<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.applications.title') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <x-toast-notification />

            <!-- Floating Toolbar -->
            <div class="flex flex-col md:flex-row justify-between items-center bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 gap-5">
                
                <!-- Tabs -->
                <div class="flex space-x-1 bg-gray-50 p-1.5 rounded-xl border border-gray-100">
                    <a href="{{ route('job-applications.index') }}" 
                       class="flex-1 sm:flex-none justify-center px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 flex items-center {{ !request('archived') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-indigo-600 hover:bg-white/50' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        {{ __('app.common.active') }}
                    </a>
                    <a href="{{ route('job-applications.index', ['archived' => 'true']) }}" 
                       class="flex-1 sm:flex-none justify-center px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 flex items-center {{ request('archived') == 'true' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-indigo-600 hover:bg-white/50' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        {{ __('app.common.archived') }}
                    </a>
                </div>

                <!-- Search & Filters -->
                <div class="flex items-center w-full md:w-auto">
                    <form method="GET" action="{{ route('job-applications.index') }}" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        @if(request('archived'))
                            <input type="hidden" name="archived" value="true">
                        @endif
                        
                        <!-- Status Filter -->
                        <div class="relative">
                            <select name="status" onchange="this.form.submit()" class="appearance-none pl-4 pr-10 py-2.5 rounded-xl border-gray-200 bg-gray-50 hover:bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-700 font-medium transition-colors cursor-pointer w-full sm:w-48">
                                <option value="">{{ __('app.common.all_statuses') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('app.applications.status_pending') }}</option>
                                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>{{ __('app.applications.status_accepted') }}</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('app.applications.status_rejected') }}</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>

                        <!-- Search Input -->
                        <div class="relative group w-full sm:w-64">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-indigo-400 group-focus-within:text-indigo-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   class="pl-10 block w-full rounded-xl border-gray-200 bg-gray-50 hover:bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 transition-colors" 
                                   placeholder="{{ __('app.common.search') }}">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Applications Table -->
            <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.applications.applicant') }}</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.applications.position_company') }}</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.applications.status') }}</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($jobApplications as $jobApplication)
                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                    <td class="px-6 py-5">
                                        @php $applicant = $jobApplication->user; @endphp
                                        <div class="flex items-center">
                                            @if($applicant)
                                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-700 font-bold text-lg mr-4 border border-indigo-50 shadow-sm group-hover:scale-105 transition-transform">
                                                    {{ strtoupper(substr($applicant->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    @if(request('archived') != 'true')
                                                        <a href="{{ route('job-applications.show', $jobApplication->id) }}" class="text-sm font-bold text-gray-900 hover:text-indigo-600 transition-colors">
                                                            {{ $applicant->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-sm font-bold text-gray-900">{{ $applicant->name }}</span>
                                                    @endif
                                                    <div class="text-xs text-gray-500 mt-0.5 flex items-center font-medium">
                                                        <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                        {{ $applicant->email }}
                                                    </div>
                                                </div>
                                            @else
                                                 <span class="text-sm text-gray-500 italic">{{ __('app.applications.unknown_applicant') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="text-sm text-gray-900 font-bold mb-0.5">{{ $jobApplication->jobVacancy?->title ?? 'N/A' }}</div>
                                        <div class="text-xs text-indigo-600 font-semibold flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $jobApplication->jobVacancy?->company?->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        @php
                                            $statusValue = $jobApplication->status->value;
                                            
                                            $statusStyles = [
                                                'accepted' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'rejected' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'pending'  => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            ];
                                            
                                            $style = $statusStyles[$statusValue] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold border {{ $style['bg'] }} {{ $style['text'] }} {{ $style['border'] }} shadow-sm">
                                            <svg class="w-4 h-4 mr-1.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $style['icon'] }}"></path></svg>
                                            {{ __('app.applications.status_' . $statusValue) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-2">
                                            @if(request('archived') == 'true')
                                                <form action="{{ route('job-applications.restore', $jobApplication->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors border border-emerald-100 shadow-sm" 
                                                            title="{{ __('app.common.restore') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('job-applications.show', $jobApplication->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg transition-colors border border-indigo-100" title="{{ __('app.applications.view_resume') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </a>
                                                <a href="{{ route('job-applications.edit', $jobApplication->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg transition-colors border border-indigo-100" title="{{ __('app.common.edit') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <x-confirm-popover action="{{ route('job-applications.destroy', $jobApplication->id) }}" question="{{ __('app.applications.confirm_archive') }}">
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
                    
                    @if($jobApplications->isEmpty())
                        <div class="py-12">
                            <x-empty-state
                                icon="clipboard"
                                title="{{ __('app.empty_states.applications.title') }}"
                                description="{{ __('app.empty_states.applications.description') }}"
                            />
                        </div>
                    @endif
                </div>

                @if($jobApplications->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $jobApplications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>