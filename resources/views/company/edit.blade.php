@php
    if (auth()->user()->role == 'admin') {
        $formAction = route('companies.update', ['company' => $company->id, 'redirectToList' => request('redirectToList')]);
    } else if (auth()->user()->role == 'company_owner') {
        $formAction = route('my-company.update');
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.companies.edit_title') . ' - ' . $company->name }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
            <form action="{{ $formAction }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Company Details -->
                <div class="mb-8 p-8 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ __('app.companies.details') }}</h3>
                            <p class="text-sm text-gray-500 font-medium">{{ __('app.companies.enter_details') }}</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div class="space-y-1">
                            <label for="name" class="block text-sm font-semibold text-gray-700">{{ __('app.companies.form_name') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" placeholder="{{ __('app.companies.name_placeholder') }}"
                                    class="pl-10 block w-full rounded-xl shadow-sm transition-all sm:text-sm py-2.5 bg-gray-50 focus:bg-white {{ $errors->has('name') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }}">
                            </div>
                            @error('name')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center font-medium"><svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="address" class="block text-sm font-semibold text-gray-700">{{ __('app.companies.form_address') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <input type="text" name="address" id="address" value="{{ old('address', $company->address) }}" placeholder="{{ __('app.companies.address_placeholder') }}"
                                    class="pl-10 block w-full rounded-xl shadow-sm transition-all sm:text-sm py-2.5 bg-gray-50 focus:bg-white {{ $errors->has('address') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }}">
                            </div>
                            @error('address')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center font-medium"><svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="industry" class="block text-sm font-semibold text-gray-700">{{ __('app.companies.form_industry') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <select name="industry" id="industry"
                                    class="pl-10 block w-full rounded-xl shadow-sm border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-all sm:text-sm py-2.5 bg-gray-50 focus:bg-white">
                                    @foreach ($industries as $industry)
                                        <option value="{{ $industry }}" {{ old('industry', $company->industry) == $industry ? 'selected' : '' }}>{{ $industry }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('industry')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center font-medium"><svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="website" class="block text-sm font-semibold text-gray-700">{{ __('app.companies.form_website') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                </div>
                                <input type="text" name="website" id="website" value="{{ old('website', $company->website) }}" placeholder="{{ __('app.companies.website_placeholder') }}"
                                    class="pl-10 block w-full rounded-xl shadow-sm transition-all sm:text-sm py-2.5 bg-gray-50 focus:bg-white {{ $errors->has('website') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }}">
                            </div>
                            @error('website')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center font-medium"><svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Company Owner -->
                <div class="mb-8 p-8 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ __('app.companies.owner_section') }}</h3>
                            <p class="text-sm text-gray-500 font-medium">{{ __('app.companies.enter_owner_details') }}</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div class="space-y-1">
                            <label for="owner_name" class="block text-sm font-semibold text-gray-700">{{ __('app.companies.owner_name') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name', $company->owner->name) }}" placeholder="{{ __('app.companies.owner_name_placeholder') }}"
                                    class="pl-10 block w-full rounded-xl shadow-sm transition-all sm:text-sm py-2.5 bg-gray-50 focus:bg-white {{ $errors->has('owner_name') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-gray-200 focus:border-emerald-500 focus:ring-emerald-500' }}">
                            </div>
                            @error('owner_name')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center font-medium"><svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Owner Email (read-only) -->
                        <div class="space-y-1">
                            <label for="owner_email" class="block text-sm font-semibold text-gray-700">{{ __('app.companies.owner_email') }}</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <input disabled type="email" name="owner_email" id="owner_email" value="{{ old('owner_email', $company->owner->email) }}"
                                    class="pl-10 block w-full rounded-xl shadow-sm sm:text-sm py-2.5 bg-gray-100 text-gray-500 cursor-not-allowed border-gray-200 focus:border-gray-200 focus:ring-0">
                            </div>
                            @error('owner_email')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center font-medium"><svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Owner Password -->
                        <div class="space-y-1">
                            <label for="owner_password" class="block text-sm font-semibold text-gray-700">{{ __('app.companies.change_owner_password') }}</label>
                            <div class="relative group" x-data="{ showPassword: false }">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input id="owner_password" class="pl-10 pr-10 block w-full rounded-xl shadow-sm transition-all sm:text-sm py-2.5 bg-gray-50 focus:bg-white {{ $errors->has('owner_password') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-gray-200 focus:border-emerald-500 focus:ring-emerald-500' }}"
                                    x-bind:type="showPassword ? 'text' : 'password'" name="owner_password"
                                    placeholder="{{ __('app.companies.owner_password_placeholder') }}" autocomplete="current-password" />

                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-emerald-600 transition-colors"
                                    @click="showPassword = !showPassword" tabindex="-1">
                                    <svg x-show="showPassword" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 5 8.268 7.943 9.542 12-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="!showPassword" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </button>
                            </div>
                            @error('owner_password')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center font-medium"><svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 items-center">
                    @if (auth()->user()->role == 'company_owner')
                        <a href="{{ route('my-company.show') }}"
                            class="px-5 py-2.5 rounded-xl font-semibold text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-all">
                            {{ __('app.common.cancel') }}
                        </a>
                    @endif

                    @if (auth()->user()->role == 'admin')
                        <a href="{{ route('companies.index') }}"
                            class="px-5 py-2.5 rounded-xl font-semibold text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-all">
                            {{ __('app.common.cancel') }}
                        </a>
                    @endif

                    <button type="submit"
                        class="inline-flex justify-center items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-bold text-white shadow-sm hover:shadow-md hover:from-indigo-700 hover:to-purple-700 transition-all duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        {{ __('app.companies.update_btn') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>