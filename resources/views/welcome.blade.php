<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#0b0d12">
        <title>CravingCorner</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Baloo+2:wght@600;700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background-color: var(--cc-paper); color: var(--cc-ink);">
        @include('partials.bg-art')

        <div class="min-h-screen flex flex-col items-center justify-center px-6 py-16 text-center relative z-10">
            <div class="brand-mark flex items-center gap-3 text-3xl mb-6 cc-animate-in">
                <x-application-logo style="width:3rem; height:3rem;" />
                CravingCorner
            </div>

            <span class="badge badge-mustard mb-5">✨ AI-powered cafeteria ordering</span>

            <h1 class="font-display cc-animate-in" style="font-size:clamp(2.1rem, 5vw, 3.2rem); font-weight:700; max-width:42rem; line-height:1.15; color: var(--cc-ink);">
                Order smarter. <span style="background: linear-gradient(135deg, var(--cc-mustard), var(--cc-gold)); -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;">Eat happier.</span>
            </h1>
            <p class="cc-animate-in" style="margin-top:1.1rem; max-width:30rem; color: var(--cc-text-muted); font-size:1.05rem;">
                Browse the menu, get AI picks tailored to your taste, and track your order — all in one beautifully simple place.
            </p>

            <div class="cc-animate-in" style="margin-top:2.25rem; display:flex; align-items:center; gap:1rem; flex-wrap:wrap; justify-content:center;">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-mustard" style="padding:0.85rem 2rem; font-size:0.95rem;">
                        Go to dashboard →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-mustard" style="padding:0.85rem 2rem; font-size:0.95rem;">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-outline" style="padding:0.85rem 2rem; font-size:0.95rem;">
                            Create account
                        </a>
                    @endif
                @endauth
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-16 max-w-4xl w-full cc-animate-stagger">
                <div class="glass-panel p-6 text-left">
                    <div class="stat-icon mb-3">🍽️</div>
                    <p class="font-semibold" style="color: var(--cc-ink);">Curated menu</p>
                    <p class="text-sm mt-1" style="color: var(--cc-text-muted);">Fresh food & beverages, filtered your way — spice level, calories, price.</p>
                </div>
                <div class="glass-panel p-6 text-left">
                    <div class="stat-icon mb-3">✨</div>
                    <p class="font-semibold" style="color: var(--cc-ink);">Smart recommendations</p>
                    <p class="text-sm mt-1" style="color: var(--cc-text-muted);">AI-matched picks based on your taste and order history.</p>
                </div>
                <div class="glass-panel p-6 text-left">
                    <div class="stat-icon mb-3">📊</div>
                    <p class="font-semibold" style="color: var(--cc-ink);">Live tracking</p>
                    <p class="text-sm mt-1" style="color: var(--cc-text-muted);">Track every order from kitchen to pickup in real time.</p>
                </div>
            </div>
        </div>
    </body>
</html>
