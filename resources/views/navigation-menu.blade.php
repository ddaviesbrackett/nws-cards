<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('account') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        Home
                    </x-nav-link>
                    <x-nav-link href="{{ route('edit') }}" :active="request()->routeIs('edit')">
                        Order
                    </x-nav-link>
                    <x-nav-link href="{{ route('account') }}" :active="request()->routeIs('account')">
                        Account
                    </x-nav-link>
                    <x-nav-link href="{{ route('leaderboard') }}" :active="request()->routeIs('leaderboard')">
                        Leaderboard
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            </button>
                            @else
                            <span class="inline-flex rounded-md">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                    {{ Auth::user()->name }}

                                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            @if (request()->user()->isadmin())
                            <!-- Administration -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Administration
                            </div>
                            <x-dropdown-link href="{{ route('admin-orders') }}" :active="request()->routeIs('admin-orders')">
                                Orders
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin-impersonateList') }}" :active="request()->routeIs('admin-impersonateList')">
                                Change Another User
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin-expenses') }}" :active="request()->routeIs('admin-expenses')">
                                Expenses
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('admin-classes') }}" :active="request()->routeIs('admin-classes')">
                                Classes
                            </x-dropdown-link>
                            {{--
                                a vestigial feature. Allowed for tracking of speculatively-bought cards at the school gate; no longer done.
                                <x-dropdown-link href="{{ route('admin-newsale') }}" :active="request()->routeIs('admin-newsale')">
                            Pointsales
                            </x-dropdown-link> --}}
                            <div class="border-t border-gray-200 dark:border-gray-600"></div>
                            @endif

                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Manage Account
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                Profile
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                            @impersonating
                            <x-dropdown-link href="{{ route('impersonate.leave') }}">
                                Stop Impersonating
                            </x-dropdown-link>
                            @endImpersonating
                        </x-slot>
                    </x-dropdown>
                </div>
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
            <x-responsive-nav-link href="{{ route('edit') }}" :active="request()->routeIs('edit')">
                Order
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('account') }}" :active="request()->routeIs('account')">
                Account
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('leaderboard') }}" :active="request()->routeIs('leaderboard')">
                Leaderboard
            </x-responsive-nav-link>
        </div>
        @if (request()->user()->isadmin())
        <!-- Administration -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <x-responsive-nav-link href="{{ route('admin-orders') }}" :active="request()->routeIs('admin-orders')">
                Orders
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('admin-impersonateList') }}" :active="request()->routeIs('admin-impersonateList')">
                Change Another User
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('admin-expenses') }}" :active="request()->routeIs('admin-expenses')">
                Expenses
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('admin-classes') }}" :active="request()->routeIs('admin-classes')">
                Classes
            </x-responsive-nav-link>
            {{--
                a vestigial feature. Allowed for tracking of speculatively-bought cards at the school gate; no longer done.
                <x-responsive-nav-link href="{{ route('admin-newsale') }}" :active="request()->routeIs('admin-newsale')">
            Pointsales
            </x-responsive-nav-link>
            --}}
        </div>
        @endif
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center px-4">
                <div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    Profile
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
                @impersonating
                <x-responsive-nav-link href="{{ route('impersonate.leave') }}">
                    Stop Impersonating
                </x-responsive-nav-link>
                @endImpersonating
            </div>
        </div>
    </div>
</nav>