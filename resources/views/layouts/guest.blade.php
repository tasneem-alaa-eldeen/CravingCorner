<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0b0d12">

        <title>{{ config('app.name', 'CravingCorner') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Baloo+2:wght@600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="color: var(--cc-ink);">
        @include('partials.bg-art')
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-10 sm:pt-0 px-4">
            <a href="/" class="brand-mark flex items-center gap-2 text-2xl">
                <x-application-logo style="width:2.6rem; height:2.6rem;" />
                CravingCorner
            </a>

            <div class="w-full sm:max-w-md mt-8 px-7 py-8 glass-panel overflow-hidden">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs" style="color: var(--cc-text-muted);">Freshly baked, always on time.</p>
        </div>
    </body>
</html>
