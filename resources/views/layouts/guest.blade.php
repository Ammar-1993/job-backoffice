<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-50 via-white to-purple-50 relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
                <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-gradient-to-br from-indigo-200/40 to-purple-200/40 blur-3xl mix-blend-multiply"></div>
                <div class="absolute top-[60%] -right-[10%] w-[40%] h-[60%] rounded-full bg-gradient-to-tl from-purple-200/40 to-pink-200/40 blur-3xl mix-blend-multiply"></div>
            </div>

            <div class="z-10 mb-8 transform transition-transform hover:scale-105 duration-300">
                <a href="/" class="flex flex-col items-center">
                    <div class="w-20 h-20 bg-white rounded-2xl shadow-lg flex items-center justify-center p-3 mb-3 border border-gray-100">
                        <x-application-logo class="w-full h-full fill-current text-indigo-600" />
                    </div>
                    <span class="text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 tracking-tight">
                        {{ config('app.name', 'Laravel') }}
                    </span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-8 bg-white/80 backdrop-blur-xl shadow-2xl border border-white sm:rounded-3xl z-10 relative">
                <!-- Inner soft shadow -->
                <div class="absolute inset-0 rounded-3xl shadow-[inset_0_0_20px_rgba(255,255,255,0.5)] pointer-events-none"></div>
                
                <div class="relative z-20">
                    {{ $slot }}
                </div>
            </div>
            
            <div class="mt-8 text-center text-sm text-gray-500 font-medium z-10">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. {{ __('app.common.all_rights_reserved') ?? 'All rights reserved.' }}
            </div>
        </div>
    </body>
</html>
