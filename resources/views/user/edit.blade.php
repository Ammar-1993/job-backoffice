<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="h-10 w-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 shadow-sm border border-indigo-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('app.users.edit_password_title') }}
                </h2>
                <p class="text-sm font-medium text-gray-500">{{ $user->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-toast-notification />

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Header Gradient -->
                <div class="h-24 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600"></div>

                <div class="px-6 sm:px-8 pb-8 -mt-10">
                    <!-- Profile Header -->
                    <div class="flex items-end mb-8 space-x-5">
                        <div class="h-20 w-20 rounded-2xl bg-white p-1.5 shadow-sm border border-gray-100 flex-shrink-0">
                            <div class="h-full w-full bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center text-indigo-700 font-extrabold text-3xl">
                                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="pb-2">
                            <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                            <div class="flex items-center text-sm font-medium text-gray-500 mt-1 space-x-3">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $user->email }}
                                </span>
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'company_owner' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'job_seeker' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    ];
                                    $roleLabel = ucwords(str_replace('_', ' ', $user->role));
                                    $badgeColor = $roleColors[$user->role] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                @endphp
                                <span class="px-2 py-0.5 inline-flex text-xs font-bold rounded-lg border {{ $badgeColor }}">
                                    {{ $roleLabel }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('users.update', ['user' => $user->id]) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100">
                            <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                {{ __('app.users.change_password') }}
                            </h4>
                            
                            <div class="space-y-1">
                                <label for="password" class="block text-sm font-semibold text-gray-700">
                                    {{ __('app.users.change_password') }}
                                </label>
                                <div class="relative group" x-data="{ showPassword: false }">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                    </div>
                                    <input id="password" 
                                           x-bind:type="showPassword ? 'text' : 'password'" 
                                           name="password"
                                           autocomplete="new-password"
                                           class="pl-10 block w-full rounded-xl border-gray-200 bg-white shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200 py-2.5 sm:text-sm" />

                                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                                        @click="showPassword = !showPassword" tabindex="-1">
                                        <!-- Eye Icon Open -->
                                        <svg x-show="showPassword" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 5 8.268 7.943 9.542 12-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <!-- Eye Icon Closed -->
                                        <svg x-show="!showPassword" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5" style="display: none;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-50">
                            <a href="{{ route('users.index') }}"
                               class="inline-flex justify-center items-center px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm">
                                {{ __('app.common.cancel') }}
                            </a>
                            <button type="submit"
                               class="inline-flex justify-center items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl text-sm font-bold text-white hover:from-indigo-700 hover:to-purple-700 transition-all shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ __('app.users.update_password_btn') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>