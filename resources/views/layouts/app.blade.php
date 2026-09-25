<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CravingCorner') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Baloo+2:wght@600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet" />

        {{-- Chart.js is bundled through resources/js/app.js (npm), not a CDN
             script — this is the only script tag this layout needs. --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }">

        @include('partials.bg-art')

        {{-- Sidebar drawer (unchanged, hidden off-canvas until the hamburger opens it) --}}
        @include('layouts.navigation')

        {{-- Top bar: hamburger + brand, always visible --}}
        <div class="cc-topbar" style="display:flex; align-items:center; justify-content:space-between; padding:0.75rem 1.25rem;">
            <div style="display:flex; align-items:center; gap:0.9rem;">
                <button type="button" @click="sidebarOpen = ! sidebarOpen" class="cc-hamburger">
                    <svg style="width:1.5rem; height:1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <a href="{{ route('dashboard') }}" class="brand-mark" style="display:flex; align-items:center; gap:0.4rem; font-size:1.15rem;">
                    <x-application-logo style="width:1.7rem; height:1.7rem;" />
                    CravingCorner
                </a>
            </div>
            <span style="color: rgba(247,244,236,0.6); font-size:0.8rem;">{{ Auth::user()->name }}</span>
        </div>

        {{-- Top navbar (quick links) — rendered exactly once, right here.
             Do not add another @include('partials.navbar') anywhere else
             in this file or it will render twice. --}}
        @include('partials.navbar')

        @isset($header)
            <header class="card-plain" style="border-radius:0; border-left:none; border-right:none; border-top:none;">
                <div style="max-width:80rem; margin:0 auto; padding:1.25rem 1.5rem;">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="cc-animate-in">
            {{ $slot }}
        </main>

        @include('partials.chat-widget')
    </body>
</html>
