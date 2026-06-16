<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.users.title') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-toast-notification />

            <!-- Premium Floating Toolbar -->
            <div class="bg-white/80 backdrop-blur-xl p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 transition-all hover:shadow-md">
                
                <!-- Tabs -->
                <div class="flex p-1 bg-gray-100/80 rounded-xl w-full md:w-auto">
                    <a href="{{ route('users.index') }}" 
                       class="flex-1 md:flex-none justify-center px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 flex items-center {{ !request('archived') ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-gray-200/50' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-200/50' }}">
                       <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        {{ __('app.users.active_users') }}
                    </a>
                    <a href="{{ route('users.index', ['archived' => 'true']) }}" 
                       class="flex-1 md:flex-none justify-center px-5 py-2.5 rounded-lg text-sm font-bold transition-all duration-300 flex items-center {{ request('archived') == 'true' ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-gray-200/50' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-200/50' }}">
                       <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        {{ __('app.users.archived_users') }}
                    </a>
                </div>

                <!-- Search -->
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <form method="GET" action="{{ route('users.index') }}" class="relative w-full md:w-80 group">
                        @if(request('archived'))
                            <input type="hidden" name="archived" value="true">
                        @endif
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="pl-11 block w-full rounded-xl border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-300 py-2.5" 
                               placeholder="{{ __('app.users.search_placeholder') }}">
                    </form>
                </div>
            </div>

            <!-- Users Table (Desktop) -->
            <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">{{ __('app.users.details') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">{{ __('app.users.role') }}</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-black text-gray-500 uppercase tracking-wider">{{ __('app.users.status') }}</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-black text-gray-500 uppercase tracking-wider">{{ __('app.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse ($users as $user)
                            <tr class="hover:bg-indigo-50/30 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-700 font-extrabold text-xl mr-4 flex-shrink-0 border border-indigo-50 shadow-sm group-hover:scale-105 transition-transform">
                                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 group-hover:text-indigo-700 transition-colors">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleColors = [
                                            'admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            'company_owner' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'job_seeker' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        ];
                                        $roleLabel = ucwords(str_replace('_', ' ', $user->role));
                                        $badgeColor = $roleColors[$user->role] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                    @endphp
                                    <span class="px-3 py-1.5 inline-flex text-xs font-bold rounded-lg border {{ $badgeColor }}">
                                        {{ $roleLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if(request('archived'))
                                        <span class="px-3 py-1.5 inline-flex text-xs font-bold rounded-lg border bg-rose-50 text-rose-700 border-rose-200">{{ __('app.categories.archived') }}</span>
                                    @elseif(auth()->id() == $user->id)
                                        <div class="inline-flex flex-col items-center justify-center" title="{{ __('app.users.cannot_deactivate_self') }}">
                                            <span class="px-3 py-1.5 inline-flex text-xs font-bold rounded-lg border {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <span class="text-[10px] text-gray-400 font-semibold mt-1">{{ __('app.users.your_account') }}</span>
                                        </div>
                                    @else
                                        <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 {{ $user->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}" 
                                                    role="switch" 
                                                    aria-checked="{{ $user->is_active ? 'true' : 'false' }}"
                                                    title="{{ $user->is_active ? __('app.users.click_to_deactivate') : __('app.users.click_to_activate') }}">
                                                <span aria-hidden="true" 
                                                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $user->is_active ? 'translate-x-5' : 'translate-x-0' }}">
                                                </span>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end items-center space-x-2">
                                        @if(request('archived') == 'true')
                                            <form action="{{ route('users.restore', $user->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl text-sm font-bold transition-all duration-200 shadow-sm border border-emerald-100" title="{{ __('app.users.restore_title') }}">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                    {{ __('app.common.restore') }}
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center justify-center w-9 h-9 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-xl transition-colors shadow-sm border border-indigo-100" title="{{ __('app.common.edit') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            @if(auth()->user()->id != $user->id)
                                                <x-confirm-popover action="{{ route('users.destroy', $user->id) }}" question="{{ __('app.users.confirm_archive') }}">
                                                    <button type="button" class="inline-flex items-center justify-center w-9 h-9 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl transition-colors shadow-sm border border-rose-100" title="{{ __('app.common.archive') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </x-confirm-popover>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Users Mobile Cards -->
            <div class="md:hidden grid grid-cols-1 gap-4">
                @forelse ($users as $user)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative group hover:shadow-md transition-all">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-700 font-extrabold text-xl border border-indigo-50 shadow-sm">
                                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 leading-tight">
                                        {{ $user->name }}
                                    </h3>
                                    <p class="text-sm font-semibold text-gray-500 mt-1">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-50">
                            <!-- Role -->
                            <div class="flex items-center">
                                @php
                                    $roleColorsMobile = [
                                        'admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'company_owner' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'job_seeker' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    ];
                                    $roleLabelMobile = ucwords(str_replace('_', ' ', $user->role));
                                    $badgeColorMobile = $roleColorsMobile[$user->role] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                @endphp
                                <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-lg border {{ $badgeColorMobile }}">
                                    {{ $roleLabelMobile }}
                                </span>
                            </div>
                            
                            <!-- Status -->
                            <div class="flex justify-end items-center">
                                @if(request('archived'))
                                    <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-lg border bg-rose-50 text-rose-700 border-rose-200">{{ __('app.categories.archived') }}</span>
                                @elseif(auth()->id() == $user->id)
                                    <span class="px-2.5 py-1 inline-flex text-xs font-bold rounded-lg border {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                @else
                                    <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="inline-flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $user->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}" 
                                                role="switch" 
                                                aria-checked="{{ $user->is_active ? 'true' : 'false' }}">
                                            <span aria-hidden="true" 
                                                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $user->is_active ? 'translate-x-5' : 'translate-x-0' }}">
                                            </span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 border-t border-gray-50 mt-4 flex items-center justify-between space-x-2">
                            @if(request('archived') == 'true')
                                <form action="{{ route('users.restore', $user->id) }}" method="POST" class="w-full">
                                    @csrf @method('PUT')
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl text-sm font-bold transition-all shadow-sm border border-emerald-100">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        {{ __('app.common.restore') }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('users.edit', $user->id) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-amber-50 border border-amber-100 text-amber-600 hover:bg-amber-100 rounded-xl text-sm font-bold transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    {{ __('app.common.edit') }}
                                </a>
                                @if(auth()->user()->id != $user->id)
                                    <div class="flex-1">
                                        <x-confirm-popover action="{{ route('users.destroy', $user->id) }}" question="{{ __('app.users.confirm_archive') }}">
                                            <button type="button" class="w-full inline-flex justify-center items-center px-4 py-2 bg-rose-50 border border-rose-100 text-rose-600 hover:bg-rose-100 rounded-xl text-sm font-bold transition-colors shadow-sm">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                {{ __('app.common.archive') }}
                                            </button>
                                        </x-confirm-popover>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                @endforelse
            </div>

            <!-- Empty State -->
            @if($users->isEmpty())
                <div class="py-8">
                    <x-empty-state
                        icon="users"
                        title="{{ __('app.empty_states.users.title') }}"
                        description="{{ __('app.empty_states.users.description') }}"
                    />
                </div>
            @endif
            
            <!-- Pagination -->
            @if($users->hasPages())
                <div class="mt-6 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>