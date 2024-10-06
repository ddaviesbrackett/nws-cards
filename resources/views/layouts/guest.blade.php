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

        <!-- Styles -->
        @livewireStyles
        @stack('scripts')
    </head>
    <body>
        <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}">
                                <x-application-mark class="block h-9 w-auto" />
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                                Home
                            </x-nav-link>
                            @auth
                                <x-nav-link href="{{ route('edit') }}" :active="request()->routeIs('edit')">
                                    Order
                                </x-nav-link>
                            @else
                                <x-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                                    Order
                                </x-nav-link>
                            @endauth
                            <x-nav-link href="{{ route('leaderboard') }}" :active="request()->routeIs('leaderboard')">
                                Leaderboard
                            </x-nav-link>
                            @if(isset($nav))
                                {{$nav}}
                            @endif
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <!-- Login -->
                        @auth
                            <x-nav-link href="{{ route('account') }}">Account</x-nav-link>
                        @else
                            <x-nav-link href="{{ route('login') }}">Log In</x-nav-link>
                        @endauth
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        Home
                    </x-responsive-nav-link>
                    @auth
                        <x-responsive-nav-link href="{{ route('edit') }}" :active="request()->routeIs('edit')">
                            Order
                        </x-responsive-nav-link>
                    @else
                        <x-responsive-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                            Order
                        </x-responsive-nav-link>
                    @endauth
                    <x-responsive-nav-link href="{{ route('leaderboard') }}" :active="request()->routeIs('leaderboard')">
                        Leaderboard
                    </x-responsive-nav-link>
                    @if(isset($responsive))
                        {{ $responsive }}
                    @endif
                </div>

                <!-- Responsive Login -->
                <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                    <div class="mt-3 space-y-1">
                        @auth
                            <x-responsive-nav-link href="{{ route('account') }}" :active="request()->routeIs('account')">
                                Account
                            </x-responsive-nav-link>
                        @else
                            <x-responsive-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                                Log In
                            </x-responsive-nav-link>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif
        <div class="font-sans text-gray-900 dark:text-gray-100 dark:bg-gray-800 antialiased">
            {{ $slot }}
        </div>
        @stack('latescripts')
        @livewireScripts
    </body>
</html>
