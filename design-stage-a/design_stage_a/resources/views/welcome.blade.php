<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CravingCorner</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Baloo+2:wght@600;700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background-color: var(--cc-paper); color: var(--cc-ink);">
        <div class="min-h-screen flex flex-col items-center justify-center px-6 text-center">
            <div class="brand-mark" style="display:flex; align-items:center; gap:0.6rem; font-size:2rem; margin-bottom:1.5rem;">
                <x-application-logo style="width:2.75rem; height:2.75rem; fill: var(--cc-mustard);" />
                CravingCorner
            </div>

            <h1 class="font-display" style="font-size:2.25rem; font-weight:700; max-width:36rem; line-height:1.2;">
                Order smarter. Eat happier.
            </h1>
            <p style="margin-top:1rem; max-width:28rem; color: var(--cc-text-muted);">
                Browse the menu, get AI picks tailored to your taste, and track your order — all in one place.
            </p>

            <div style="margin-top:2rem; display:flex; align-items:center; gap:1rem;">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-mustard" style="padding:0.75rem 1.75rem; font-size:0.95rem;">
                        Go to dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-mustard" style="padding:0.75rem 1.75rem; font-size:0.95rem;">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-outline" style="padding:0.75rem 1.75rem; font-size:0.95rem;">
                            Register
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </body>
</html>
