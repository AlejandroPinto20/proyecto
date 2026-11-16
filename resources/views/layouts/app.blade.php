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
        @vite([
            'resources/css/app.css', 
            'resources/css/calendar.css', 
            'resources/css/activities.css',
            'resources/js/app.js', 
            'resources/js/real-time-notifications.js',
            'resources/js/activities.js',
            'resources/js/notifications.js',
            'resources/js/volunteer-assistant.js'
        ])
        
        <!-- Alpine.js for advanced components -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-blue-400 via-purple-400 to-orange-400">
        <div class="min-h-screen bg-gradient-to-br from-blue-400 via-purple-400 to-orange-400">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
                @isset($slot)
                    {{ $slot }}
                @endisset
            </main>
            <x-volunteer-assistant />
        </div>
    </body>
</html>
