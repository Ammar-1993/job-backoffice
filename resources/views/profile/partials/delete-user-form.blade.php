<section class="space-y-6">
    <header class="flex items-center space-x-3">
        <div class="p-2 bg-red-100 text-red-600 rounded-lg shadow-sm border border-red-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div>
            <h2 class="text-lg font-bold text-red-700">
                {{ __('app.profile.delete_account') }}
            </h2>
            <p class="mt-1 text-sm text-red-500 font-medium">
                {{ __('app.profile.delete_account_desc') }}
            </p>
        </div>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex justify-center items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 border border-transparent rounded-xl text-sm font-bold text-white hover:from-red-700 hover:to-rose-700 transition-all shadow-sm hover:shadow-md"
    >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        {{ __('app.profile.delete_account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-start space-x-4 mb-4">
                <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h2 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                        {{ __('app.profile.delete_confirm_title') }}
                    </h2>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 font-medium">
                            {{ __('app.profile.delete_confirm_desc') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <label for="password" class="sr-only">{{ __('app.auth.password') }}</label>
                <div class="relative group" x-data="{ showPassword: false }">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input id="password" name="password" x-bind:type="showPassword ? 'text' : 'password'" class="pl-10 pr-10 block w-full sm:w-3/4 rounded-xl border-gray-200 bg-white focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200 sm:text-sm py-2.5" placeholder="{{ __('app.auth.password') }}" autocomplete="current-password" />
                    
                    <button type="button" class="absolute inset-y-0 right-0 sm:right-[25%] pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors" @click="showPassword = !showPassword" tabindex="-1">
                        <svg x-show="showPassword" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 5 8.268 7.943 9.542 12-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="!showPassword" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3 pt-4 border-t border-gray-50">
                <button type="button" x-on:click="$dispatch('close')" class="inline-flex justify-center items-center px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm">
                    {{ __('app.common.cancel') }}
                </button>

                <button type="submit" class="inline-flex justify-center items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 border border-transparent rounded-xl text-sm font-bold text-white hover:from-red-700 hover:to-rose-700 transition-all shadow-sm hover:shadow-md ms-3">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    {{ __('app.profile.delete_account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
