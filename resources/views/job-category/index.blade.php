<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.categories.title') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <x-toast-notification />

            <!-- Floating Toolbar -->
            <div class="flex flex-col md:flex-row justify-between items-center bg-white p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 gap-5">
                
                <!-- Tabs -->
                <div class="flex space-x-1 bg-gray-50 p-1.5 rounded-xl border border-gray-100">
                    <a href="{{ route('job-categories.index') }}" 
                       class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 {{ !request('archived') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-indigo-600 hover:bg-white/50' }}">
                        {{ __('app.categories.active') }}
                    </a>
                    <a href="{{ route('job-categories.index', ['archived' => 'true']) }}" 
                       class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 {{ request('archived') == 'true' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-indigo-600 hover:bg-white/50' }}">
                        {{ __('app.categories.archived') }}
                    </a>
                </div>

                <!-- Search & Add -->
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                    <form method="GET" action="{{ route('job-categories.index') }}" class="relative w-full sm:w-72">
                         @if(request('archived'))
                            <input type="hidden" name="archived" value="true">
                        @endif
                         <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-indigo-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="pl-10 block w-full rounded-xl border-gray-200 bg-gray-50 hover:bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 transition-colors" 
                               placeholder="{{ __('app.categories.search_placeholder') }}...">
                    </form>

                    <a href="{{ route('job-categories.create') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 border border-transparent rounded-xl font-bold text-sm text-white shadow-md hover:shadow-lg hover:from-indigo-500 hover:to-indigo-600 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 active:scale-95 w-full sm:w-auto justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        {{ __('app.categories.add_category') }}
                    </a>
                </div>
            </div>

            <!-- Job Category Table -->
            <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-2xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.categories.name') }}</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($categories as $category)
                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                    <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-gray-900">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-700 font-extrabold text-lg mr-4 border border-indigo-50 shadow-sm group-hover:scale-105 transition-transform">
                                                {{ mb_strtoupper(mb_substr($category->name, 0, 1)) }}
                                            </div>
                                            <span class="group-hover:text-indigo-700 transition-colors">{{ $category->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-2">
                                            @if(request('archived') == 'true')
                                                <form action="{{ route('job-categories.restore', $category->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg transition-colors border border-emerald-100 font-semibold shadow-sm">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                        {{ __('app.common.restore') }}
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('job-categories.edit', $category->id) }}" class="inline-flex items-center justify-center w-8 h-8 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition-colors border border-amber-100" title="{{ __('app.common.edit') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <x-confirm-popover action="{{ route('job-categories.destroy', $category->id) }}" question="{{ __('app.categories.confirm_archive') }}">
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
                    
                    @if($categories->isEmpty())
                        <div class="py-12">
                            <x-empty-state
                                icon="folder"
                                title="{{ __('app.empty_states.categories.title') }}"
                                description="{{ __('app.empty_states.categories.description') }}"
                                actionText="{{ __('app.empty_states.categories.action') }}"
                                actionUrl="{{ route('job-categories.create') }}"
                            />
                        </div>
                    @endif
                </div>

                @if($categories->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>