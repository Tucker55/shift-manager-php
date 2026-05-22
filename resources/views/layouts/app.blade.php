<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', config('app.name', 'Bean & Brew'))</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>[x-cloak] { display: none !important; }</style>
    </head>
    <body class="font-sans antialiased app-page-bg" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')
            <main class="flex-1">
                {{ $slot }}
            </main>
            <footer class="py-8 mt-12">
                <div class="max-w-6xl mx-auto px-5 sm:px-8 text-center text-sm opacity-60" style="color: var(--coffee-800)">
                    ☕ {{ config('app.name') }} — staff shift rota
                </div>
            </footer>
        </div>
    </body>
</html>
