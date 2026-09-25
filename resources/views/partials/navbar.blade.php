@php
    $isAdmin = auth()->user()->isAdmin();

    $cartCount = collect(session('cart', []))->sum('quantity');

    $quickLinks = $isAdmin ? [
        ['href' => route('dashboard'), 'label' => 'Home', 'active' => request()->routeIs('dashboard')],
        ['href' => route('admin.categories.index'), 'label' => 'Categories', 'active' => request()->routeIs('admin.categories.*')],
        ['href' => route('admin.food-items.index'), 'label' => 'Food', 'active' => request()->routeIs('admin.food-items.*')],
        ['href' => route('admin.beverages.index'), 'label' => 'Beverages', 'active' => request()->routeIs('admin.beverages.*')],
        ['href' => route('admin.orders.index'), 'label' => 'Orders', 'active' => request()->routeIs('admin.orders.*')],
        ['href' => route('admin.statistics.index'), 'label' => 'Statistics', 'active' => request()->routeIs('admin.statistics.*')],
    ] : [
        ['href' => route('dashboard'), 'label' => 'Home', 'active' => request()->routeIs('dashboard')],
        ['href' => route('menu.index'), 'label' => 'Menu', 'active' => request()->routeIs('menu.*')],
        ['href' => route('recommendations.index'), 'label' => 'For You', 'active' => request()->routeIs('recommendations.*')],
        ['href' => route('favorites.index'), 'label' => 'Favorites', 'active' => request()->routeIs('favorites.*')],
        ['href' => route('orders.index'), 'label' => 'My Orders', 'active' => request()->routeIs('orders.*')],
    ];
@endphp

<!--
    New top navbar. The sidebar (layouts/navigation.blade.php) is kept
    exactly as-is; this sits directly below the existing hamburger/topbar
    strip in layouts/app.blade.php and gives quick, one-click access to
    the pages people jump to most, without opening the drawer.
-->
<nav class="nav-cc" style="border-top: 1px solid rgba(255,255,255,0.08);">
    <div style="max-width:80rem; margin:0 auto; padding:0 1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; overflow-x:auto;">

        <div style="display:flex; align-items:center; gap:1.1rem; white-space:nowrap;">
            @foreach ($quickLinks as $link)
                <a href="{{ $link['href'] }}" class="nav-link-cc {{ $link['active'] ? 'active' : '' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>

        <div style="display:flex; align-items:center; gap:0.9rem; white-space:nowrap;">
            @unless ($isAdmin)
                <a href="{{ route('cart.index') }}" class="nav-link-cc {{ request()->routeIs('cart.*') ? 'active' : '' }}" style="position:relative;">
                    🛒 Cart
                    @if ($cartCount > 0)
                        <span class="badge badge-mustard" style="position:absolute; top:-10px; right:-14px; padding:0 0.4rem; font-size:0.65rem;">{{ $cartCount }}</span>
                    @endif
                </a>
            @endunless
            <a href="{{ route('chatbot.index') }}" class="nav-link-cc {{ request()->routeIs('chatbot.*') ? 'active' : '' }}">💬 Assistant</a>
            <a href="{{ route('profile.edit') }}" class="nav-link-cc {{ request()->routeIs('profile.*') ? 'active' : '' }}">{{ Auth::user()->name }}</a>
        </div>

    </div>
</nav>
