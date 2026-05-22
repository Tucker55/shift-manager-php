<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Log in — {{ config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased login-page-bg" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
        <div class="min-h-screen flex flex-col lg:flex-row">
            <div class="hidden lg:flex lg:w-1/2 flex-col justify-center px-16 xl:px-24 py-16">
                <x-brand-logo size="lg" variant="light" class="mb-12 pointer-events-none" />
                <h1 class="text-4xl xl:text-5xl font-bold text-white leading-tight">
                    Your team.<br>Your shifts.<br>One rota.
                </h1>
                <p class="mt-6 text-lg text-amber-100/70 max-w-md leading-relaxed">
                    Pick up open shifts, see your schedule, and know who's on the bar — built for Bean & Brew.
                </p>
            </div>

            <div class="flex-1 flex items-center justify-center px-6 py-12 lg:py-16">
                <div class="w-full max-w-md">
                    <div class="lg:hidden mb-8 flex justify-center">
                        <x-brand-logo size="md" variant="light" />
                    </div>
                    <div class="login-card px-8 py-10">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
