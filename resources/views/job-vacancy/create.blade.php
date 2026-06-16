<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.jobs.create_title') }}
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
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-gray-900">{{ __('app.jobs.create_title') }}</h3>
                            <p class="text-sm text-gray-500 mt-1 font-medium">{{ __('app.jobs.enter_details') ?? 'Fill out the form below to publish a new job.' }}</p>
                        </div>
                    </div>

                    <form action="{{ route('job-vacancies.store') }}" method="POST">
                        @csrf

                        <!-- Job Vacancy Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.jobs.form_title') }} <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    </div>
                                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                                        class="{{ $errors->has('title') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 hover:bg-white' }} pl-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors"
                                        placeholder="e.g. Senior Frontend Developer" required autofocus>
                                </div>
                                @error('title')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.jobs.form_location') }} <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <input type="text" name="location" id="location" value="{{ old('location') }}"
                                        class="{{ $errors->has('location') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 hover:bg-white' }} pl-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors"
                                        placeholder="e.g. New York, NY" required>
                                </div>
                                @error('location')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Salary -->
                            <div>
                                <label for="salary" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.jobs.form_salary') }} <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <input type="number" name="salary" id="salary" value="{{ old('salary') }}"
                                        class="{{ $errors->has('salary') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 hover:bg-white' }} pl-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors"
                                        placeholder="e.g. 120000" required>
                                </div>
                                @error('salary')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.jobs.form_type') }} <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                    </div>
                                    <select name="type" id="type"
                                        class="{{ $errors->has('type') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 hover:bg-white' }} pl-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors appearance-none cursor-pointer" required>
                                        <option value="" disabled selected>{{ __('app.jobs.select_type') ?? 'Select Job Type' }}</option>
                                        <option value="Full-Time" {{ old('type') == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                                        <option value="Contract" {{ old('type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="Remote" {{ old('type') == 'Remote' ? 'selected' : '' }}>Remote</option>
                                        <option value="Hybrid" {{ old('type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                @error('type')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Company -->
                            <div>
                                <label for="companyId" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.jobs.form_company') }} <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <select name="companyId" id="companyId"
                                        class="{{ $errors->has('companyId') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 hover:bg-white' }} pl-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors appearance-none cursor-pointer" required>
                                        <option value="" disabled selected>{{ __('app.jobs.select_company') ?? 'Select Company' }}</option>
                                        @foreach ($companies as $company)
                                            <option {{ old('companyId') == $company->id ? 'selected' : '' }} value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                @error('companyId')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="md:col-span-2">
                                <label for="jobCategoryId" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.jobs.form_category') }} <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    </div>
                                    <select name="jobCategoryId" id="jobCategoryId"
                                        class="{{ $errors->has('jobCategoryId') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 hover:bg-white' }} pl-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors appearance-none cursor-pointer" required>
                                        <option value="" disabled selected>{{ __('app.jobs.select_category') ?? 'Select Job Category' }}</option>
                                        @foreach ($jobCategories as $jobCategory)
                                            <option {{ old('jobCategoryId') == $jobCategory->id ? 'selected' : '' }} value="{{ $jobCategory->id }}">{{ $jobCategory->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                @error('jobCategoryId')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.jobs.form_description') }}</label>
                                <div class="relative">
                                    <textarea rows="6" name="description" id="description"
                                        class="{{ $errors->has('description') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 hover:bg-white' }} block w-full rounded-xl shadow-sm sm:text-sm py-3 px-4 transition-colors resize-y"
                                        placeholder="{{ __('app.jobs.desc_placeholder') ?? 'Describe the job role, responsibilities, and requirements...' }}">{{ old('description') }}</textarea>
                                </div>
                                @error('description')
                                    <p class="mt-2 text-sm text-rose-600 font-medium flex items-center"><svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('job-vacancies.index') }}"
                                class="inline-flex items-center px-5 py-2.5 rounded-xl font-bold text-sm text-gray-600 hover:text-gray-900 bg-white border border-gray-200 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-300">
                                {{ __('app.common.cancel') }}
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 border border-transparent rounded-xl font-bold text-sm text-white shadow-md hover:shadow-lg hover:from-indigo-500 hover:to-indigo-600 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 active:scale-95">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                {{ __('app.jobs.add_job_btn') ?? 'Publish Job' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>