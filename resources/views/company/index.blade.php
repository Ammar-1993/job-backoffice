<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.companies.directory_title') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-toast-notification />

            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-100 gap-4 transition-all hover:shadow-md">
                
                <!-- Tabs -->
                <div class="flex space-x-2 bg-slate-50 p-1.5 rounded-xl border border-slate-100">
                    <a href="{{ route('companies.index') }}" 
                       class="flex-1 sm:flex-none justify-center px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 flex items-center {{ !request('archived') ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-slate-200/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-200/50' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        {{ __('app.companies.active_companies') }}
                    </a>
                    <a href="{{ route('companies.index', ['archived' => 'true']) }}" 
                       class="flex-1 sm:flex-none justify-center px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 flex items-center {{ request('archived') == 'true' ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-slate-200/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-200/50' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        {{ __('app.companies.archived_companies') }}
                    </a>
                </div>

                <!-- Search & Add -->
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <form method="GET" action="{{ route('companies.index') }}" class="relative w-full sm:w-72 group">
                        @if(request('archived'))
                            <input type="hidden" name="archived" value="true">
                        @endif
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="pl-10 block w-full rounded-xl border-slate-200 bg-slate-50 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-all sm:text-sm py-2.5" 
                               placeholder="{{ __('app.companies.search_placeholder') }}">
                    </form>

                    <a href="{{ route('companies.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-md hover:shadow-lg hover:from-indigo-700 hover:to-purple-700 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 active:scale-[0.98] w-full sm:w-auto justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        {{ __('app.companies.add_company') }}
                    </a>
                </div>
            </div>

            <!-- Companies Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.companies.name') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.companies.details') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.companies.website') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('app.common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($companies as $company)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-11 w-11 flex-shrink-0 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg border border-indigo-100 shadow-sm transform transition-transform group-hover:scale-105">
                                                {{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    @if(request('archived') != 'true')
                                                        <a href="{{ route('companies.show', $company->id) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                                                            {{ $company->name }}
                                                        </a>
                                                    @else
                                                        {{ $company->name }}
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $company->industry }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 line-clamp-1">{{ $company->address }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($company->website)
                                            <a href="{{ $company->website }}" target="_blank" class="text-sm text-indigo-500 hover:text-indigo-700 flex items-center">
                                                {{ __('app.companies.visit_site') }} <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-2">
                                            @if(request('archived') == 'true')
                                                <form action="{{ route('companies.restore', $company->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 rounded-lg transition-colors border border-emerald-100" 
                                                            title="{{ __('app.common.restore') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('companies.edit', $company->id) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg transition-colors border border-indigo-100" 
                                                   title="{{ __('app.common.edit') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <x-confirm-popover action="{{ route('companies.destroy', $company->id) }}" question="{{ __('app.companies.confirm_archive') }}">
                                                    <button type="button" 
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 rounded-lg transition-colors border border-rose-100 ml-1" 
                                                            title="{{ __('app.common.archive') }}">
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
                    
                    @if($companies->isEmpty())
                        <x-empty-state
                            icon="building"
                            title="{{ __('app.empty_states.companies.title') }}"
                            description="{{ __('app.empty_states.companies.description') }}"
                            actionText="{{ __('app.empty_states.companies.action') }}"
                            actionUrl="{{ route('companies.create') }}"
                        />
                    @endif
                </div>

                @if($companies->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $companies->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>