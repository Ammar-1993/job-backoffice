<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="h-10 w-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 shadow-sm border border-indigo-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('app.profile.title') }}
                </h2>
                <p class="text-sm font-medium text-gray-500">{{ auth()->user()->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-toast-notification />

            <!-- Profile Header Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="h-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600"></div>
                <div class="px-6 sm:px-8 pb-8 -mt-12 flex flex-col sm:flex-row sm:items-end sm:space-x-5">
                    <div class="h-24 w-24 rounded-2xl bg-white p-1.5 shadow-sm border border-gray-100 flex-shrink-0 mx-auto sm:mx-0">
                        <div class="h-full w-full bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center text-indigo-700 font-extrabold text-4xl">
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0 pb-2 text-center sm:text-left flex-1">
                        <h3 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h3>
                        <div class="flex flex-col sm:flex-row sm:items-center text-sm font-medium text-gray-500 mt-1 space-y-2 sm:space-y-0 sm:space-x-4 justify-center sm:justify-start">
                            <span class="flex items-center justify-center sm:justify-start">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ auth()->user()->email }}
                            </span>
                            @php
                                $roleColors = [
                                    'admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'company_owner' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'job_seeker' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                ];
                                $roleLabel = ucwords(str_replace('_', ' ', auth()->user()->role));
                                $badgeColor = $roleColors[auth()->user()->role] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                            @endphp
                            <span class="px-2 py-0.5 inline-flex justify-center text-xs font-bold rounded-lg border {{ $badgeColor }}">
                                {{ $roleLabel }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-2xl">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-6 sm:p-8 bg-white shadow-sm border border-gray-100 rounded-2xl">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-6 sm:p-8 bg-red-50/30 shadow-sm border border-red-100 rounded-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
                        <svg class="w-32 h-32 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="max-w-2xl relative z-10">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
