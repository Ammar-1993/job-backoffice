<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.categories.create_title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
                
                <!-- Background Accent -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-bl-full opacity-50"></div>
                <div class="absolute top-0 left-0 w-2 h-full bg-indigo-500"></div>

                <div class="p-8 md:p-10 relative z-10">
                    <div class="flex items-center mb-8 pb-6 border-b border-gray-100">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mr-4 border border-indigo-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-gray-900">{{ __('app.categories.create_title') }}</h3>
                            <p class="text-sm text-gray-500 mt-1 font-medium">{{ __('app.categories.create_description') ?? 'Fill in the information below to add a new category.' }}</p>
                        </div>
                    </div>

                    <form action="{{ route('job-categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-8">
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.categories.form_name') }} <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    class="{{ $errors->has('name') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500 bg-rose-50' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 hover:bg-white' }} pl-11 block w-full rounded-xl shadow-sm sm:text-sm py-3 transition-colors"
                                    placeholder="{{ __('app.categories.name_placeholder') ?? 'e.g. Software Engineering' }}" required autofocus>
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-rose-600 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('job-categories.index') }}"
                                class="inline-flex items-center px-5 py-2.5 rounded-xl font-bold text-sm text-gray-600 hover:text-gray-900 bg-white border border-gray-200 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-300">
                                {{ __('app.common.cancel') }}
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-md hover:shadow-lg hover:from-indigo-700 hover:to-purple-700 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 active:scale-[0.98]">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                {{ __('app.categories.add_btn') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>