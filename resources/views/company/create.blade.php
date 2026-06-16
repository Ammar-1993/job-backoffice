<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.companies.create_title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
                
                <!-- Background Accent -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-bl-full opacity-50"></div>
                <div class="absolute top-0 left-0 w-2 h-full bg-indigo-500"></div>

                <div class="p-8 md:p-10 relative z-10">
                    <div class="flex items-center mb-8 pb-6 border-b border-gray-100">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mr-4 border border-indigo-100 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-gray-900">{{ __('app.companies.create_title') }}</h3>
                            <p class="text-sm text-gray-500 mt-1 font-medium">{{ __('app.companies.enter_details') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('companies.store') }}" method="POST">
                        @csrf

                        <!-- Company Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            
                            <!-- Company Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.companies.form_name') }} <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="{{ __('app.companies.name_placeholder') }}"
                                        class="{{ $errors->has('name') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 hover:bg-white' }} pl-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors" required autofocus>
                                </div>
                                @error('name')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="address" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.companies.form_address') }} <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <input type="text" name="address" id="address" value="{{ old('address') }}" placeholder="{{ __('app.companies.address_placeholder') }}"
                                        class="{{ $errors->has('address') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 hover:bg-white' }} pl-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors" required>
                                </div>
                                @error('address')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Industry -->
                            <div>
                                <label for="industry" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.companies.form_industry') }} <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <select name="industry" id="industry"
                                        class="{{ $errors->has('industry') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 hover:bg-white' }} pl-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors appearance-none cursor-pointer" required>
                                        @foreach ($industries as $industry)
                                            <option value="{{ $industry }}" {{ old('industry') == $industry ? 'selected' : '' }}>{{ $industry }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                @error('industry')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Website -->
                            <div class="md:col-span-2">
                                <label for="website" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.companies.form_website') }}</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                    </div>
                                    <input type="url" name="website" id="website" value="{{ old('website') }}" placeholder="{{ __('app.companies.website_placeholder') }}"
                                        class="{{ $errors->has('website') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 hover:bg-white' }} pl-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors">
                                </div>
                                @error('website')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <!-- Company Owner Header -->
                        <div class="flex items-center mt-10 mb-8 pb-6 border-b border-gray-100">
                            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 mr-4 border border-emerald-100 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-extrabold text-gray-900">{{ __('app.companies.owner_section') }}</h3>
                                <p class="text-sm text-gray-500 mt-1 font-medium">{{ __('app.companies.enter_owner_details') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Owner Name -->
                            <div class="md:col-span-2">
                                <label for="owner_name" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.companies.owner_name') }} <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-emerald-600 transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name') }}" placeholder="{{ __('app.companies.owner_name_placeholder') }}"
                                        class="{{ $errors->has('owner_name') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50 hover:bg-white' }} pl-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors" required>
                                </div>
                                @error('owner_name')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Owner Email -->
                            <div>
                                <label for="owner_email" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.companies.owner_email') }} <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-emerald-600 transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <input type="email" name="owner_email" id="owner_email" value="{{ old('owner_email') }}" placeholder="{{ __('app.companies.owner_email_placeholder') }}"
                                        class="{{ $errors->has('owner_email') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50 hover:bg-white' }} pl-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors" required>
                                </div>
                                @error('owner_email')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Owner Password -->
                            <div x-data="{ showPassword: false }">
                                <label for="owner_password" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.companies.owner_password') }} <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-emerald-600 transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                    <input id="owner_password" class="{{ $errors->has('owner_password') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50 hover:bg-white' }} pl-11 pr-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors"
                                        x-bind:type="showPassword ? 'text' : 'password'" name="owner_password" required
                                        placeholder="{{ __('app.companies.owner_password_placeholder') }}" autocomplete="new-password" />

                                    <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-emerald-600 transition-colors"
                                        @click="showPassword = !showPassword" tabindex="-1">
                                        <svg x-show="showPassword" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 5 8.268 7.943 9.542 12-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg x-show="!showPassword" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                    </button>
                                </div>
                                @error('owner_password')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('companies.index') }}"
                                class="inline-flex items-center px-5 py-2.5 rounded-xl font-bold text-sm text-gray-600 hover:text-gray-900 bg-white border border-gray-200 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-300">
                                {{ __('app.common.cancel') }}
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-md hover:shadow-lg hover:from-indigo-700 hover:to-purple-700 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 active:scale-[0.98]">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                {{ __('app.companies.add_btn') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>