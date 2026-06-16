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
                       class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-300 {{ !request('archived') ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-slate-200/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-200/50' }}">
                        {{ __('app.companies.active_companies') }}
                    </a>
                    <a href="{{ route('companies.index', ['archived' => 'true']) }}" 
                       class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-300 {{ request('archived') == 'true' ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-slate-200/50' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-200/50' }}">
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
                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider shadow-md hover:shadow-lg hover:from-indigo-500 hover:to-indigo-600 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
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
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 hover:text-emerald-800 rounded-lg text-sm font-semibold transition-all duration-200">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                        {{ __('app.common.restore') }}
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('companies.edit', $company->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 hover:text-indigo-800 rounded-lg text-sm font-semibold transition-all duration-200">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    {{ __('app.common.edit') }}
                                                </a>
                                                <x-confirm-popover action="{{ route('companies.destroy', $company->id) }}" question="{{ __('app.companies.confirm_archive') }}">
                                                    <button type="button" class="inline-flex items-center px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 hover:text-rose-800 rounded-lg text-sm font-semibold transition-all duration-200 ml-1">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                                        {{ __('app.common.archive') }}
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