<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if($jobApplication->user)
                {{ $jobApplication->user->name }}
            @else
                <span class="text-sm text-gray-500">{{ __('app.applications.user_deleted') }}</span>
            @endif
            | {{ __('app.applications.applied_to') }}
            @if($jobApplication->jobVacancy)
                {{ $jobApplication->jobVacancy->title }}
            @else
                <span class="text-sm text-gray-500">{{ __('app.applications.job_removed') }}</span>
            @endif
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('job-applications.index') }}"
                class="bg-gray-200 text-gray-800 hover:bg-gray-300 px-4 py-2 rounded-md">← {{ __('app.common.back') }}</a>
        </div>

        <!-- Wrapper -->
        <div class="w-full mx-auto p-6 bg-white rounded-lg shadow">
            <!-- Application Details -->
            <div>
                <h3 class="text-lg font-bold">{{ __('app.applications.details_title') }}</h3>
                <p><strong>{{ __('app.applications.applicant') }}:</strong>
                    @if($jobApplication->user)
                        {{ $jobApplication->user->name }}
                    @else
                        <span class="text-sm text-gray-600">{{ __('app.applications.user_deleted') }}</span>
                    @endif
                </p>
                <p><strong>{{ __('app.applications.job_vacancy') }}:</strong>
                    @if($jobApplication->jobVacancy)
                        {{ $jobApplication->jobVacancy->title }}
                    @else
                        <span class="text-sm text-gray-600">{{ __('app.applications.job_removed') }}</span>
                    @endif
                </p>
                <p><strong>{{ __('app.dashboard.company') }}:</strong>
                    @if($jobApplication->jobVacancy && $jobApplication->jobVacancy->company)
                        {{ $jobApplication->jobVacancy->company->name }}
                    @else
                        <span class="text-sm text-gray-600">{{ __('app.applications.company_deleted') }}</span>
                    @endif
                </p>
                <p><strong>{{ __('app.applications.status') }}:</strong> <span
                        class="@if($jobApplication->status->value == 'accepted') text-green-600 @elseif($jobApplication->status->value == 'rejected') text-red-600 @else text-purple-600 @endif">{{ __('app.applications.status_' . $jobApplication->status->value) }}
                    </span></p>
                <p><strong>{{ __('app.applications.resume_tab') }}:</strong>
                    @php
                        /** @var \Illuminate\Filesystem\FilesystemAdapter $cloudDisk */
                        $cloudDisk = Storage::disk('cloud');
                    @endphp
                    
                    @if($jobApplication->resume && $jobApplication->resume->fileUri)
                        <a class="text-blue-500 hover:text-blue-700 underline" 
                           href="{{ $cloudDisk->url($jobApplication->resume->fileUri) }}" 
                           target="_blank">
                            {{ $jobApplication->resume->filename ?? $jobApplication->resume->fileUri }}
                        </a>
                    @else
                        <span class="text-sm text-gray-600">{{ __('app.applications.no_resume') }}</span>
                    @endif
                </p>
            </div>

            <!-- Edit and Archive Buttons -->
            <div class="flex justify-end space-x-4 mb-6">
                <a href="{{ route('job-applications.edit', ['job_application' => $jobApplication->id, 'redirectToList' => 'false']) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">{{ __('app.common.edit') }}</a>
                <form action="{{ route('job-applications.destroy', ['job_application' => $jobApplication->id]) }}"
                    method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">{{ __('app.common.archive') }}</button>
                </form>
            </div>

            <!-- Tabs Navigation -->
            <div class="mb-6">
                <ul class="flex space-x-4">
                    <li>
                        <a href="{{ route('job-applications.show', ['job_application' => $jobApplication->id, 'tab' => 'resume']) }}"
                            class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') == 'resume' || request('tab') == '' ? 'border-b-2 border-blue-500' : '' }}">{{ __('app.applications.resume_tab') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('job-applications.show', ['job_application' => $jobApplication->id, 'tab' => 'AIFeedback']) }}"
                            class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') == 'AIFeedback' ? 'border-b-2 border-blue-500' : '' }}">{{ __('app.applications.ai_feedback_tab') }}</a>
                    </li>
                </ul>
            </div>


            <!-- Tab Content -->
            <div>
                <!-- Resume Tab -->
                <div id="resume" class="{{ request('tab') == 'resume' || request('tab') == '' ? 'block space-y-6' : 'hidden' }}">
                    
                    <!-- Summary -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="text-lg font-bold text-gray-800 mb-3 border-b border-gray-300 pb-2">{{ __('app.applications.summary') }}</h4>
                        <div class="text-gray-700 leading-relaxed text-sm whitespace-pre-wrap">@if(is_array($jobApplication->resume->summary)){{ implode("\n", $jobApplication->resume->summary) }}@else{{ $jobApplication->resume->summary }}@endif</div>
                    </div>

                    <!-- Skills -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-300 pb-2">{{ __('app.applications.skills') }}</h4>
                        @if(is_array($jobApplication->resume->skills))
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($jobApplication->resume->skills as $skill)
                                    <span class="inline-block bg-blue-100 border border-blue-200 text-blue-800 rounded-full px-4 py-1.5 text-sm font-semibold shadow-sm">{{ is_string($skill) ? $skill : json_encode($skill) }}</span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-gray-700 text-sm">{{ $jobApplication->resume->skills }}</div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Experience -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-200">
                            <h4 class="text-lg font-bold text-gray-800 mb-5 border-b border-gray-300 pb-2">{{ __('app.applications.experience') }}</h4>
                            @if(is_array($jobApplication->resume->experience))
                                <div class="space-y-6">
                                    @foreach($jobApplication->resume->experience as $exp)
                                        @if(is_array($exp))
                                            <div class="relative pl-5 border-l-2 border-indigo-400">
                                                <div class="absolute w-3 h-3 bg-indigo-500 rounded-full -left-[7px] top-1.5 ring-4 ring-gray-50"></div>
                                                <h5 class="font-bold text-gray-900 text-base">{{ $exp['job_title'] ?? $exp['title'] ?? 'N/A' }}</h5>
                                                <div class="text-sm text-indigo-600 font-semibold mb-2">
                                                    {{ $exp['company'] ?? 'N/A' }} 
                                                    <span class="mx-1 text-gray-400">•</span> 
                                                    <span class="text-gray-500 font-medium">{{ $exp['duration'] ?? $exp['dates'] ?? 'N/A' }}</span>
                                                </div>
                                                <p class="text-sm text-gray-700 mt-2 leading-relaxed">{{ $exp['description'] ?? '' }}</p>
                                            </div>
                                        @else
                                            <div class="text-gray-700 text-sm">{{ $exp }}</div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <pre class="text-sm whitespace-pre-wrap text-gray-700">{{ $jobApplication->resume->experience }}</pre>
                            @endif
                        </div>

                        <!-- Education -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm border border-gray-200">
                            <h4 class="text-lg font-bold text-gray-800 mb-5 border-b border-gray-300 pb-2">{{ __('app.applications.education') }}</h4>
                            @if(is_array($jobApplication->resume->education))
                                <div class="space-y-6">
                                    @foreach($jobApplication->resume->education as $edu)
                                        @if(is_array($edu))
                                            <div class="relative pl-5 border-l-2 border-emerald-400">
                                                <div class="absolute w-3 h-3 bg-emerald-500 rounded-full -left-[7px] top-1.5 ring-4 ring-gray-50"></div>
                                                <h5 class="font-bold text-gray-900 text-base">{{ $edu['degree'] ?? 'N/A' }}</h5>
                                                <div class="text-sm text-emerald-600 font-semibold mb-1">
                                                    {{ $edu['institution'] ?? $edu['university'] ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500 font-medium flex items-center gap-1 mt-1">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    {{ $edu['graduation_year'] ?? $edu['dates'] ?? 'N/A' }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-gray-700 text-sm">{{ $edu }}</div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <pre class="text-sm whitespace-pre-wrap text-gray-700">{{ $jobApplication->resume->education }}</pre>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- AI Feedback Tab -->
                <div id="AIFeedback" class="{{ request('tab') == 'AIFeedback' ? 'block space-y-6' : 'hidden' }}">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Score Card -->
                        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 p-8 rounded-lg shadow-sm border border-indigo-100 flex flex-col justify-center items-center relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-200 rounded-full opacity-50"></div>
                            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-16 h-16 bg-blue-200 rounded-full opacity-50"></div>
                            
                            <h4 class="text-lg font-bold text-indigo-900 mb-2 relative z-10">{{ __('app.applications.ai_score') }}</h4>
                            <div class="text-6xl font-black text-indigo-600 relative z-10 my-4 flex items-baseline">
                                {{ $jobApplication->aiGeneratedScore ?? '0' }}<span class="text-3xl text-indigo-400 ml-1">%</span>
                            </div>
                            <div class="text-sm text-indigo-600 font-medium relative z-10 bg-white/60 px-3 py-1 rounded-full">AI Match Index</div>
                        </div>
                        
                        <!-- Feedback Card -->
                        <div class="lg:col-span-2 bg-gray-50 p-8 rounded-lg shadow-sm border border-gray-200 relative">
                            <div class="absolute top-6 right-6 text-indigo-200">
                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-800 mb-5 border-b border-gray-300 pb-2 inline-block pr-8">{{ __('app.applications.ai_feedback') }}</h4>
                            <div class="text-gray-700 leading-relaxed text-sm whitespace-pre-wrap relative z-10">{{ $jobApplication->aiGeneratedFeedback ?: __('app.applications.no_feedback_yet') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
