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
                            <p class="text-sm text-gray-500">{{ __('app.companies.enter_details') }}</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.companies.form_name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}"
                                class="{{ $errors->has('name') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} block w-full rounded-xl shadow-sm transition-colors sm:text-sm py-2.5 bg-gray-50 focus:bg-white">
                            @error('name')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.companies.form_address') }}</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $company->address) }}"
                                class="{{ $errors->has('address') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} block w-full rounded-xl shadow-sm transition-colors sm:text-sm py-2.5 bg-gray-50 focus:bg-white">
                            @error('address')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="industry" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.companies.form_industry') }}</label>
                            <select name="industry" id="industry"
                                class="block w-full rounded-xl shadow-sm border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 transition-colors sm:text-sm py-2.5 bg-gray-50 focus:bg-white">
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry }}" {{ old('industry', $company->industry) == $industry ? 'selected' : '' }}>{{ $industry }}</option>
                                @endforeach
                            </select>
                            @error('industry')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="website" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.companies.form_website') }}</label>
                            <input type="text" name="website" id="website" value="{{ old('website', $company->website) }}"
                                class="{{ $errors->has('website') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} block w-full rounded-xl shadow-sm transition-colors sm:text-sm py-2.5 bg-gray-50 focus:bg-white">
                            @error('website')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
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
                            <p class="text-sm text-gray-500">{{ __('app.companies.enter_owner_details') }}</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label for="owner_name" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.companies.owner_name') }}</label>
                            <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name', $company->owner->name) }}"
                                class="{{ $errors->has('owner_name') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} block w-full rounded-xl shadow-sm transition-colors sm:text-sm py-2.5 bg-gray-50 focus:bg-white">
                            @error('owner_name')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Owner Email (read-only) -->
                        <div>
                            <label for="owner_email" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.companies.owner_email') }}</label>
                            <input disabled type="email" name="owner_email" id="owner_email" value="{{ old('owner_email', $company->owner->email) }}"
                                class="{{ $errors->has('owner_email') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} block w-full rounded-xl shadow-sm sm:text-sm py-2.5 bg-gray-100 text-gray-500 cursor-not-allowed">
                            @error('owner_email')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Owner Password -->
                        <div>
                            <label for="owner_password" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('app.companies.change_owner_password') }}</label>
                            <div class="relative" x-data="{ showPassword: false }">
                                <x-text-input id="owner_password" class="{{ $errors->has('owner_password') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} block w-full rounded-xl shadow-sm transition-colors sm:text-sm py-2.5 bg-gray-50 focus:bg-white pr-10"
                                    x-bind:type="showPassword ? 'text' : 'password'" name="owner_password"
                                    autocomplete="current-password" />

                                <button type="button" class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-indigo-600 transition-colors"
                                    @click="showPassword = !showPassword">

                                    <!-- Eye Icon Open -->
                                    <svg x-show="showPassword" width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                        <path d="M15.0007 12C15.0007 13.6569 13.6576 15 12.0007 15C10.3439 15 9.00073 13.6569 9.00073 12C9.00073 10.3431 10.3439 9 12.0007 9C13.6576 9 15.0007 10.3431 15.0007 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M12.0012 5C7.52354 5 3.73326 7.94288 2.45898 12C3.73324 16.0571 7.52354 19 12.0012 19C16.4788 19 20.2691 16.0571 21.5434 12C20.2691 7.94291 16.4788 5 12.0012 5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                    <!-- Eye Icon Closed -->
                                    <svg x-show="!showPassword" width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                        <path d="M2.99902 3L20.999 21M9.8433 9.91364C9.32066 10.4536 8.99902 11.1892 8.99902 12C8.99902 13.6569 10.3422 15 11.999 15C12.8215 15 13.5667 14.669 14.1086 14.133M6.49902 6.64715C4.59972 7.90034 3.15305 9.78394 2.45703 12C3.73128 16.0571 7.52159 19 11.9992 19C13.9881 19 15.8414 18.4194 17.3988 17.4184M10.999 5.04939C11.328 5.01673 11.6617 5 11.9992 5C16.4769 5 20.2672 7.94291 21.5414 12C21.2607 12.894 20.8577 13.7338 20.3522 14.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                            @error('owner_password')
                                <p class="mt-1.5 text-sm text-rose-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
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
                        class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 border border-transparent rounded-xl font-bold text-white shadow-md hover:shadow-lg hover:from-indigo-500 hover:to-indigo-600 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        {{ __('app.companies.update_btn') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>