<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="flex" x-data="{ sidebarOpen: window.innerWidth >= 768 }">
        @include('layouts.navigation')

        <div class="flex-1 min-h-screen bg-gray-50 bg-[url('https://grainy-gradients.vercel.app/noise.svg')]">
            <!-- Page Heading with Toggle Button -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="py-4 px-4 w-full flex items-center space-x-4">
                        <!-- Toggle Button (Attractive Hamburger Menu) -->
                        <button 
                            @click="sidebarOpen = !sidebarOpen"
                            aria-label="Toggle sidebar"
                            class="md:hidden p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-200 border border-gray-200 group flex-shrink-0">
                            <!-- Hamburger Icon (transforms to X) -->
                            <!-- Hamburger Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        
                        <!-- Page Title -->
                        <div class="flex-1">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
    
    <!-- Toast Notifications -->
    <x-toast-notification />
</body>

</html>