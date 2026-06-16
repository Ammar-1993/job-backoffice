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
                class="inline-flex items-center px-4 py-2 bg-white text-gray-700 font-semibold rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 border border-gray-100 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('app.common.back') }}
            </a>
        </div>

        <!-- Premium Profile Header -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8 relative">
            <div class="h-32 bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 relative">
                <div class="absolute inset-0 bg-white/10" style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 20px 20px; opacity: 0.2;"></div>
            </div>
            
            <div class="px-8 pb-8 pt-0 relative flex flex-col md:flex-row items-start md:items-end justify-between">
                <div class="flex items-end">
                    <!-- Avatar -->
                    <div class="h-28 w-28 rounded-2xl bg-white p-1.5 shadow-md -mt-14 relative z-10">
                        <div class="w-full h-full rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-4xl font-extrabold text-indigo-700 border border-indigo-50">
                            {{ $jobApplication->user ? strtoupper(substr($jobApplication->user->name, 0, 1)) : '?' }}
                        </div>
                    </div>
                    
                    <div class="ml-6 mb-2">
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                            {{ $jobApplication->user ? $jobApplication->user->name : __('app.applications.user_deleted') }}
                        </h1>
                        <div class="flex flex-wrap items-center mt-2 gap-4">
                            <div class="flex items-center text-sm font-medium text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $jobApplication->jobVacancy ? $jobApplication->jobVacancy->title : __('app.applications.job_removed') }}
                            </div>
                            <div class="flex items-center text-sm font-medium text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                {{ ($jobApplication->jobVacancy && $jobApplication->jobVacancy->company) ? $jobApplication->jobVacancy->company->name : __('app.applications.company_deleted') }}
                            </div>
                            
                            @php
                                $statusValue = $jobApplication->status->value;
                                $statusStyles = [
                                    'accepted' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'rejected' => 'bg-rose-100 text-rose-800 border-rose-200',
                                    'pending'  => 'bg-amber-100 text-amber-800 border-amber-200',
                                ];
                                $style = $statusStyles[$statusValue] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                            @endphp
                            <span class="px-3 py-1.5 rounded-lg text-sm font-bold border {{ $style }}">
                                {{ __('app.applications.status_' . $statusValue) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3 mt-6 md:mt-0">
                    <a href="{{ route('job-applications.edit', ['job_application' => $jobApplication->id, 'redirectToList' => 'false']) }}"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 hover:text-indigo-600 transition-all duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        {{ __('app.common.edit') }}
                    </a>
                    <form action="{{ route('job-applications.destroy', ['job_application' => $jobApplication->id]) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-white border border-rose-100 text-rose-600 font-bold rounded-xl shadow-sm hover:shadow-md hover:bg-rose-50 transition-all duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            {{ __('app.common.archive') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Tabs Navigation -->
            <div class="border-b border-gray-100">
                <nav class="flex px-6 space-x-8" aria-label="Tabs">
                    <a href="{{ route('job-applications.show', ['job_application' => $jobApplication->id, 'tab' => 'resume']) }}"
                        class="group relative py-4 px-2 font-bold text-sm transition-colors duration-300 {{ request('tab') == 'resume' || request('tab') == '' ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2 {{ request('tab') == 'resume' || request('tab') == '' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            {{ __('app.applications.resume_tab') }}
                        </span>
                        @if(request('tab') == 'resume' || request('tab') == '')
                            <span class="absolute bottom-0 left-0 w-full h-1 bg-indigo-600 rounded-t-full"></span>
                        @endif
                    </a>
                    
                    <a href="{{ route('job-applications.show', ['job_application' => $jobApplication->id, 'tab' => 'AIFeedback']) }}"
                        class="group relative py-4 px-2 font-bold text-sm transition-colors duration-300 {{ request('tab') == 'AIFeedback' ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2 {{ request('tab') == 'AIFeedback' ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            {{ __('app.applications.ai_feedback_tab') }}
                        </span>
                        @if(request('tab') == 'AIFeedback')
                            <span class="absolute bottom-0 left-0 w-full h-1 bg-indigo-600 rounded-t-full"></span>
                        @endif
                    </a>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-8">
                <!-- Resume Tab -->
                <div id="resume" class="{{ request('tab') == 'resume' || request('tab') == '' ? 'block space-y-8' : 'hidden' }}">
                    
                    <!-- Original File Download (if any) -->
                    @if($jobApplication->resume && $jobApplication->resume->fileUri)
                        @php
                            $cloudDisk = Storage::disk('cloud');
                        @endphp
                        <div class="flex justify-end mb-4">
                            <a href="{{ $cloudDisk->url($jobApplication->resume->fileUri) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-gray-50 text-indigo-600 font-bold rounded-xl border border-indigo-100 hover:bg-indigo-50 transition-colors shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Original Resume
                            </a>
                        </div>
                    @endif

                    <!-- Summary -->
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
                        <h4 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ __('app.applications.summary') }}
                        </h4>
                        <div class="text-gray-700 leading-relaxed text-sm whitespace-pre-wrap ml-7">@if(is_array($jobApplication->resume->summary)){{ implode("\n", $jobApplication->resume->summary) }}@else{{ $jobApplication->resume->summary }}@endif</div>
                    </div>

                    <!-- Skills -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            {{ __('app.applications.skills') }}
                        </h4>
                        @if(is_array($jobApplication->resume->skills))
                            <div class="flex flex-wrap gap-2.5">
                                @foreach($jobApplication->resume->skills as $skill)
                                    <span class="inline-flex items-center px-3.5 py-1.5 bg-indigo-50 border border-indigo-100 text-indigo-700 rounded-xl text-sm font-bold shadow-sm hover:shadow-md transition-shadow cursor-default">
                                        <svg class="w-3.5 h-3.5 mr-1.5 opacity-70" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        {{ is_string($skill) ? $skill : json_encode($skill) }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-gray-700 text-sm">{{ $jobApplication->resume->skills }}</div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Experience -->
                        <div>
                            <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center border-b border-gray-100 pb-3">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ __('app.applications.experience') }}
                            </h4>
                            @if(is_array($jobApplication->resume->experience))
                                <div class="space-y-8 pl-4 border-l-2 border-indigo-100 ml-2">
                                    @foreach($jobApplication->resume->experience as $exp)
                                        @if(is_array($exp))
                                            <div class="relative pl-6">
                                                <div class="absolute w-4 h-4 bg-indigo-500 rounded-full -left-[25px] top-1.5 ring-4 ring-white shadow-sm"></div>
                                                <h5 class="font-bold text-gray-900 text-base mb-1">{{ $exp['job_title'] ?? $exp['title'] ?? 'N/A' }}</h5>
                                                <div class="flex flex-wrap items-center text-sm mb-3">
                                                    <span class="text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded mr-2">{{ $exp['company'] ?? 'N/A' }}</span>
                                                    <span class="text-gray-500 font-semibold flex items-center">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        {{ $exp['duration'] ?? $exp['dates'] ?? 'N/A' }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-100">{{ $exp['description'] ?? '' }}</p>
                                            </div>
                                        @else
                                            <div class="text-gray-700 text-sm">{{ $exp }}</div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <pre class="text-sm whitespace-pre-wrap text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100">{{ $jobApplication->resume->experience }}</pre>
                            @endif
                        </div>

                        <!-- Education -->
                        <div>
                            <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center border-b border-gray-100 pb-3">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                                {{ __('app.applications.education') }}
                            </h4>
                            @if(is_array($jobApplication->resume->education))
                                <div class="space-y-8 pl-4 border-l-2 border-indigo-100 ml-2">
                                    @foreach($jobApplication->resume->education as $edu)
                                        @if(is_array($edu))
                                            <div class="relative pl-6">
                                                <div class="absolute w-4 h-4 bg-indigo-400 rounded-full -left-[25px] top-1.5 ring-4 ring-white shadow-sm"></div>
                                                <h5 class="font-bold text-gray-900 text-base mb-1">{{ $edu['degree'] ?? 'N/A' }}</h5>
                                                <div class="text-sm font-semibold mb-1 text-gray-800">
                                                    {{ $edu['institution'] ?? $edu['university'] ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500 font-medium flex items-center">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    {{ $edu['graduation_year'] ?? $edu['dates'] ?? 'N/A' }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-gray-700 text-sm bg-gray-50 p-4 rounded-xl border border-gray-100">{{ $edu }}</div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <pre class="text-sm whitespace-pre-wrap text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100">{{ $jobApplication->resume->education }}</pre>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- AI Feedback Tab -->
                <div id="AIFeedback" class="{{ request('tab') == 'AIFeedback' ? 'block space-y-6' : 'hidden' }}">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Score Card -->
                        <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 p-8 rounded-2xl shadow-md text-white flex flex-col justify-center items-center relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white rounded-full opacity-10"></div>
                            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-16 h-16 bg-white rounded-full opacity-10"></div>
                            
                            <h4 class="text-lg font-bold text-indigo-100 mb-2 relative z-10">{{ __('app.applications.ai_score') }}</h4>
                            <div class="text-7xl font-black text-white relative z-10 my-4 flex items-baseline drop-shadow-md">
                                {{ $jobApplication->aiGeneratedScore ?? '0' }}<span class="text-3xl text-indigo-200 ml-1">%</span>
                            </div>
                            <div class="text-sm font-bold relative z-10 bg-white/20 backdrop-blur-sm px-4 py-1.5 rounded-full shadow-inner border border-white/30">AI Match Index</div>
                        </div>
                        
                        <!-- Feedback Card -->
                        <div class="lg:col-span-2 bg-gray-50 p-8 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-bl-full opacity-50"></div>
                            <div class="absolute top-6 right-6 text-indigo-200">
                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"></path></svg>
                            </div>
                            <h4 class="text-lg font-extrabold text-gray-800 mb-5 border-b border-gray-200 pb-3 inline-block pr-8">{{ __('app.applications.ai_feedback') }}</h4>
                            <div class="text-gray-700 leading-relaxed text-sm whitespace-pre-wrap relative z-10 p-4 bg-white rounded-xl shadow-sm border border-gray-100">{{ $jobApplication->aiGeneratedFeedback ?: __('app.applications.no_feedback_yet') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
