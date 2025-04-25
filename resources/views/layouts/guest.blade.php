<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Hospital EMS') }}</title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🏥</text></svg>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-b from-blue-50 to-indigo-100">
            <div class="mb-3">
                <a href="/" class="flex flex-col items-center">
                    <x-application-logo class="w-24 h-24 fill-current text-blue-600" />
                    <span class="mt-2 text-2xl font-semibold text-blue-800">Hospital Emergency Management</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-4 px-6 py-6 bg-white shadow-lg overflow-hidden sm:rounded-lg border border-gray-200">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-sm text-gray-600">
                &copy; {{ date('Y') }} Hospital Emergency Management System. All rights reserved.
            </div>
        </div>
    </body>
</html>
