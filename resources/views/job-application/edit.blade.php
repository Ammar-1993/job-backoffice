<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.applications.edit_status_title') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <div class="max-w-3xl mx-auto p-8 bg-white rounded-2xl shadow-sm border border-gray-100">
            <form
                action="{{ route('job-applications.update', ['job_application' => $jobApplication->id, 'redirectToList' => request()->query('redirectToList')]) }}"
                method="POST">
                @csrf
                @method('PUT')

                <!-- Job Application Details -->
                <div class="mb-8 bg-gray-50 rounded-2xl p-6 border border-gray-100 shadow-inner">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                        <div class="p-2 bg-indigo-100 text-indigo-700 rounded-lg mr-3 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-gray-800">{{ __('app.applications.details_title') }}</h3>
                            <p class="text-sm text-gray-500 font-medium">Review the application details before updating the status.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">{{ __('app.applications.applicant_name') }}</label>
                            @if($jobApplication->user)
                                <span class="text-base font-semibold text-gray-900">{{ $jobApplication->user->name }}</span>
                            @else
                                <span class="text-base text-gray-500 italic">{{ __('app.applications.user_deleted') }}</span>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">{{ __('app.applications.job_vacancy') }}</label>
                            @if($jobApplication->jobVacancy)
                                <span class="text-base font-semibold text-gray-900">{{ $jobApplication->jobVacancy->title }}</span>
                            @else
                                <span class="text-base text-gray-500 italic">{{ __('app.applications.job_removed') }}</span>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">{{ __('app.dashboard.company') }}</label>
                            @if($jobApplication->jobVacancy && $jobApplication->jobVacancy->company)
                                <span class="text-base font-semibold text-gray-900">{{ $jobApplication->jobVacancy->company->name }}</span>
                            @else
                                <span class="text-base text-gray-500 italic">{{ __('app.applications.company_deleted') }}</span>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">{{ __('app.applications.ai_score') }}</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                {{ $jobApplication->aiGeneratedScore }}% Match
                            </span>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-indigo-400" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"></path></svg>
                            {{ __('app.applications.ai_feedback') }}
                        </label>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $jobApplication->aiGeneratedFeedback ?: 'No feedback provided.' }}</p>
                    </div>
                </div>

                <!-- Update Status Section -->
                <div class="mb-8 p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                    <h4 class="text-lg font-bold text-gray-800 mb-4">{{ __('app.applications.status') }}</h4>
                    <div class="relative max-w-sm">
                        <select name="status" id="status"
                            class="block w-full pl-4 pr-10 py-3 rounded-xl shadow-sm border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-semibold text-gray-700 bg-gray-50 hover:bg-white transition-colors cursor-pointer appearance-none {{ $errors->has('status') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : '' }}">
                            <option value="pending" {{ old('status', $jobApplication->status->value) == 'pending' ? 'selected' : '' }}>{{ __('app.applications.status_pending') }}
                            </option>
                            <option value="rejected" {{ old('status', $jobApplication->status->value) == 'rejected' ? 'selected' : '' }}>{{ __('app.applications.status_rejected') }}
                            </option>
                            <option value="accepted" {{ old('status', $jobApplication->status->value) == 'accepted' ? 'selected' : '' }}>{{ __('app.applications.status_accepted') }}
                            </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        @error('status')
                            <p class="mt-2 text-sm text-rose-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                        @enderror
                    </div> 
                </div>

                <div class="flex justify-end space-x-4 items-center">
                    <a href="{{ route('job-applications.index') }}"
                        class="px-5 py-2.5 rounded-xl font-semibold text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-all">
                        {{ __('app.common.cancel') }}
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 border border-transparent rounded-xl font-bold text-white shadow-md hover:shadow-lg hover:from-indigo-500 hover:to-indigo-600 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 active:scale-95">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ __('app.applications.update_status_btn') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>