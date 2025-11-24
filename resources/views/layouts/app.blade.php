<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

    <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100 dark:bg-gray-900">

        
        <!-- Static sidebar for desktop -->
        <div class="hidden md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col z-40">
            <div class="flex flex-grow flex-col overflow-y-auto border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <!-- Logo Section -->
                <div class="flex flex-shrink-0 items-center justify-center h-16 px-4 bg-indigo-700 dark:bg-gray-900 shadow-md">
                    <!-- The Logo Wrapper -->
                    <a href="{{ url('/') }}" class="flex items-center space-x-3">
                        
                        <!-- NEW Logo: Using an Image Tag (Replace the old SVG) -->
                        <img 
                            src="{{ asset('images/a1.jpg') }}" 
                            alt="VistaStock Logo" 
                            class="h-11 w-auto rounded-full object-contain"
                        >
                        <!-- The 'asset()' helper function is mandatory here. -->
                        
                        <!-- App Name -->
                        <span class="text-xl font-bold text-white tracking-wider uppercase text-center">
                            ManMade 
                        </span>
                    </a>
                </div>

                <!-- Primary Navigation Links -->
                <nav class="flex flex-1 flex-col pt-5 pb-4 space-y-1 overflow-y-auto">
                    <!-- Dashboard Link -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition duration-150">
                        <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Products Link -->
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition duration-150">
                        <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.023.832l.979 5.092c.083.42.417.759.833.84L18.847 10.5M8.618 17.5M12 17.5M15.382 17.5M10.276 4.606a1.5 1.5 0 00-1.285 1.706l1.383 6.899a.591.591 0 01-.223.509L2.25 15.75m17.06-4.5H18.75m0 0a.75.75 0 100 1.5.75.75 0 000-1.5zM12 17.25h1.5M1.5 17.25a.75.75 0 01.75-.75h2.25M17.25 10.5h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75.75.75 0 01.75-.75zm0 0l-1.383 6.899a.591.591 0 01-.223.509l-7.259 4.356a.588.588 0 01-.715-.75l1.383-6.899A1.5 1.5 0 008.618 4.606z" /></svg>
                        {{ __('Products') }}
                    </x-nav-link>

                    <!-- Categories Link (NEW) -->
                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition duration-150">
                        <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        {{ __('Categories') }}
                    </x-nav-link>

                </nav>
                
                <!-- START: User Profile and Logout Section (Desktop Sidebar) -->
                <div class="flex-shrink-0 px-4 py-2 border-t border-gray-200 dark:border-gray-700">
                    <!-- Profile Link -->
                    <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 border border-transparent">
                        <img class="h-10 w-10 rounded-full object-cover" 
                             src="{{ Auth::user()->profile_photo_url ?? 'https://placehold.co/40x40/4f46e5/ffffff?text=' . substr(Auth::user()->name, 0, 1) }}" 
                             alt="{{ Auth::user()->name }}">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-indigo-600 dark:text-indigo-400 truncate">View Profile</p>
                        </div>
                    </a>

                    <!-- Logout Form (Desktop Sidebar) -->
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center p-2 text-sm font-medium rounded-md text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-700 transition duration-150 group">
                            <svg class="mr-3 h-6 w-6 text-red-400 group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H9" /></svg>
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
                <!-- END: User Profile and Logout Section (Desktop Sidebar) -->

                <!-- Removed the old simplified User Info section here -->
            </div>
        </div>

        <!-- Mobile Sidebar (Off-Canvas Menu) -->
        <div x-show="sidebarOpen" class="relative z-50 md:hidden" role="dialog" aria-modal="true">
            <!-- Background overlay -->
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>

            <div class="fixed inset-0 flex">
                <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative mr-16 flex w-full max-w-xs flex-1">
                    
                    <!-- Close button for mobile menu -->
                    <div class="absolute top-0 left-full flex w-16 justify-center pt-5">
                        <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <!-- Mobile Sidebar Content (mirrors desktop content) -->
                    <div class="flex flex-grow flex-col overflow-y-auto bg-white dark:bg-gray-800 pb-4">
                        <!-- Mobile Logo -->
                        <div class="flex flex-shrink-0 items-center h-16 px-4 bg-indigo-600 dark:bg-indigo-700">
                            <span class="text-xl font-bold text-white tracking-wider">App Dashboard</span>
                        </div>
                        
                        <!-- Mobile Navigation Links -->
                        <nav class="flex flex-1 flex-col pt-5 pb-4 space-y-1">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition duration-150">
                                <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                {{ __('Dashboard') }}
                            </x-nav-link>

                            <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition duration-150">
                                <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.023.832l.979 5.092c.083.42.417.759.833.84L18.847 10.5M8.618 17.5M12 17.5M15.382 17.5M10.276 4.606a1.5 1.5 0 00-1.285 1.706l1.383 6.899a.591.591 0 01-.223.509L2.25 15.75m17.06-4.5H18.75m0 0a.75.75 0 100 1.5.75.75 0 000-1.5zM12 17.25h1.5M1.5 17.25a.75.75 0 01.75-.75h2.25M17.25 10.5h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75.75.75 0 01.75-.75zm0 0l-1.383 6.899a.591.591 0 01-.223.509l-7.259 4.356a.588.588 0 01-.715-.75l1.383-6.899A1.5 1.5 0 008.618 4.606z" /></svg>
                                {{ __('Products') }}
                            </x-nav-link>

                            <!-- Categories Link (NEW) -->
                            <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="text-gray-900 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md transition duration-150">
                                <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                                {{ __('Categories') }}
                            </x-nav-link>
                        </nav>
                        
                        <!-- START: User Profile and Logout Section (Mobile Sidebar) -->
                        <div class="flex-shrink-0 px-2 py-2 mt-auto border-t border-gray-200 dark:border-gray-700">
                            <!-- Profile Link -->
                            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-150">
                                <img class="h-10 w-10 rounded-full object-cover" 
                                     src="{{ Auth::user()->profile_photo_url ?? 'https://placehold.co/40x40/4f46e5/ffffff?text=' . substr(Auth::user()->name, 0, 1) }}" 
                                     alt="{{ Auth::user()->name }}">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-indigo-600 dark:text-indigo-400 truncate">View Profile</p>
                                </div>
                            </a>

                            <!-- Logout Form (Mobile Sidebar) -->
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center p-2 text-sm font-medium rounded-md text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-600 transition duration-150 group">
                                    <svg class="mr-3 h-6 w-6 text-red-400 group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H9" /></svg>
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                        <!-- END: User Profile and Logout Section (Mobile) -->
                        
                        <!-- Removed the old Mobile Logout Form and redundant info here -->
                    </div>
                </div>
            </div>
        </div>


        <div class="md:pl-64 flex flex-col flex-1">
            
            <!-- Sticky Header/Top Nav -->
            <div class="sticky top-0 z-10 flex h-16 flex-shrink-0 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
                
                <!-- Mobile menu button -->
                <button type="button" @click="sidebarOpen = true" class="border-r border-gray-200 dark:border-gray-700 px-4 text-gray-500 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                </button>
                
                <!-- Profile Dropdown and Search (Logout remains here) -->
                <div class="flex flex-1 justify-end px-4">
                    <!-- User Dropdown (Alpine.js) -->
                    <div class="ml-4 flex items-center md:ml-6" x-data="{ open: false }" @click.outside="open = false">
                        <!-- Profile Button -->
                        <button type="button" @click="open = !open" class="relative max-w-xs flex items-center rounded-full bg-white dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                            <span class="sr-only">Open user menu</span>
                            <!-- Using a placeholder image if profile_photo_url is not set -->
                            <img class="h-8 w-8 rounded-full object-cover" 
                                 src="{{ Auth::user()->profile_photo_url ?? 'https://placehold.co/32x32/374151/ffffff?text=' . substr(Auth::user()->name, 0, 1) }}" 
                                 alt="{{ Auth::user()->name }}">
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-cloak x-show="open" 
                            x-transition:enter="transition ease-out duration-200" 
                            x-transition:enter-start="transform opacity-0 scale-95" 
                            x-transition:enter-end="transform opacity-100 scale-100" 
                            x-transition:leave="transition ease-in duration-75" 
                            x-transition:leave-start="transform opacity-100 scale-100" 
                            x-transition:leave-end="transform opacity-0 scale-95" 
                            class="absolute right-4 top-14 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-700 py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            
                            <!-- Profile Link -->
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-150" role="menuitem" tabindex="-1">
                                Your Profile
                            </a>

                            <!-- Settings/Placeholder Link -->
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-150" role="menuitem" tabindex="-1">
                                Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            
            <main class="flex-1">
                
                @if (isset($header))
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <div class="py-4">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>