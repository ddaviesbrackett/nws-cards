<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('scripts')
    </head>
    <body class="font-sans antialiased text-lg">
        <x-banner />

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="text-center bg-gray-300 dark:bg-gray-700">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200">
                            {{ $header }}
                        </h2>
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="font-sans text-gray-900 dark:text-gray-100 dark:bg-gray-800 antialiased bg-gray-100 dark:bg-gray-900 pt-4">
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @stack('latescripts')
        @livewireScripts
    </body>
</html>
