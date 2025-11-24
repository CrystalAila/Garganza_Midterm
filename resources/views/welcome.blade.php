<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 dark:text-gray-100 min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center min-h-screen p-6">

            <div class="text-center mb-8">
                <h1 class="text-6xl font-extrabold text-indigo-600 dark:text-indigo-400">MANMADE INVENTORY</h1>
                <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">
                    Simplify your inventory management with ease.
                </p>
            </div>

            <div class="w-full max-w-sm p-6 bg-white dark:bg-gray-800 shadow-2xl rounded-xl">
                <h2 class="text-2xl font-bold mb-6 text-center border-b pb-3 border-gray-200 dark:border-gray-700">
                    Access Portal
                </h2>
                
                <div class="flex flex-col space-y-4">
                    
                    @guest
                        <!-- Show these links ONLY if the user is NOT logged in -->
                        
                        <!-- Login Button: Takes user to the Login FORM -->
                        <a href="{{ route('login') }}" class="w-full text-center py-3 px-4 bg-indigo-600 hover:bg-indigo-700 rounded-lg text-white font-semibold transition duration-150 shadow-md hover:shadow-lg">
                            Login to Dashboard
                        </a>

                        <!-- Register Button -->
                        <a href="{{ route('register') }}" class="w-full text-center py-3 px-4 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg text-gray-800 dark:text-gray-200 font-semibold transition duration-150 shadow-md">
                            Create Account
                        </a>
                    @endguest

                    @auth
                        <!-- Show this link ONLY if the user IS logged in -->
                        
                        <!-- Dashboard Button: Takes user directly to the Dashboard -->
                        <a href="{{ route('dashboard') }}" class="w-full text-center py-3 px-4 bg-green-600 hover:bg-green-700 rounded-lg text-white font-semibold transition duration-150 shadow-md hover:shadow-lg">
                            Go to Dashboard
                        </a>
                        
                        <!-- Logout link for authenticated users -->
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full text-center py-3 px-4 bg-red-500 hover:bg-red-600 rounded-lg text-white font-semibold transition duration-150 shadow-md">
                                Logout
                            </button>
                        </form>
                    @endauth

                </div>
            </div>


        </div>
    </body>
</html>