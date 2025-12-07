@props(['action', 'question' => 'Are you sure you want to archive this item?'])

<div x-data="{ open: false }" class="relative inline-block text-left">
    <div @click="open = true">
        {{ $slot }}
    </div>

    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 z-50 mt-2 w-64 origin-top-right rounded-lg bg-white shadow-xl ring-1 ring-black ring-opacity-5 border border-gray-100 focus:outline-none"
         style="display: none;">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900">{{ __('Archive Item') }}</h3>
                    <div class="mt-2">
                        <p class="text-xs text-gray-500">{{ $question }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex justify-end space-x-3">
                <button type="button" 
                        @click="open = false" 
                        class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('Cancel') }}
                </button>
                <form action="{{ $action }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        {{ __('Archive') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
