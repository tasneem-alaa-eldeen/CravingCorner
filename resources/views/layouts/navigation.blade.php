@php
    $isAdmin = auth()->user()->isAdmin();

    $links = $isAdmin ? [
        ['href' => route('dashboard'), 'label' => 'Home', 'icon' => '🏠', 'active' => request()->routeIs('dashboard')],
        ['href' => route('admin.categories.index'), 'label' => 'Categories', 'icon' => '🏷️', 'active' => request()->routeIs('admin.categories.*')],
        ['href' => route('admin.food-items.index'), 'label' => 'Food Items', 'icon' => '🍽️', 'active' => request()->routeIs('admin.food-items.*')],
        ['href' => route('admin.beverages.index'), 'label' => 'Beverages', 'icon' => '🥤', 'active' => request()->routeIs('admin.beverages.*')],
        ['href' => route('admin.orders.index'), 'label' => 'Orders', 'icon' => '🧾', 'active' => request()->routeIs('admin.orders.*')],
        ['href' => route('admin.users.index'), 'label' => 'Users', 'icon' => '👥', 'active' => request()->routeIs('admin.users.*')],
        ['href' => route('admin.statistics.index'), 'label' => 'Statistics', 'icon' => '📊', 'active' => request()->routeIs('admin.statistics.*')],
    ] : [
        ['href' => route('dashboard'), 'label' => 'Home', 'icon' => '🏠', 'active' => request()->routeIs('dashboard')],
        ['href' => route('menu.index'), 'label' => 'Menu', 'icon' => '📋', 'active' => request()->routeIs('menu.*')],
        ['href' => route('recommendations.index'), 'label' => 'For You', 'icon' => '✨', 'active' => request()->routeIs('recommendations.*')],
        ['href' => route('cart.index'), 'label' => 'Cart', 'icon' => '🛒', 'active' => request()->routeIs('cart.*')],
        ['href' => route('orders.index'), 'label' => 'My Orders', 'icon' => '🧾', 'active' => request()->routeIs('orders.*')],
        ['href' => route('favorites.index'), 'label' => 'Favorites', 'icon' => '❤️', 'active' => request()->routeIs('favorites.*')],
        ['href' => route('preferences.edit'), 'label' => 'Preferences', 'icon' => '⚙️', 'active' => request()->routeIs('preferences.*')],
        ['href' => route('surprise-me'), 'label' => 'Surprise Me', 'icon' => '🎲', 'active' => false],
    ];
@endphp

<!-- Backdrop -->
<div class="cc-sidebar-backdrop" :class="{ 'cc-sidebar-open': sidebarOpen }" @click="sidebarOpen = false"></div>

<!-- Drawer -->
<aside class="cc-sidebar" :class="{ 'cc-sidebar-open': sidebarOpen }">
    <div style="display:flex; flex-direction:column; height:100%;">

        <div style="display:flex; align-items:center; justify-content:space-between; padding:1.1rem 1.1rem 0.9rem;">
            <a href="{{ route('dashboard') }}" class="brand-mark" style="display:flex; align-items:center; gap:0.5rem;">
                <x-application-logo style="width:2rem; height:2rem;" />
                CravingCorner
            </a>
            <button @click="sidebarOpen = false" style="background:none; border:none; color: var(--cc-paper); cursor:pointer; font-size:1.1rem; padding:0.25rem;">✕</button>
        </div>

        <nav style="flex:1; overflow-y:auto; padding:0.5rem 0.75rem;">
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}" @click="sidebarOpen = false"
                   style="display:flex; align-items:center; gap:0.6rem; padding:0.6rem 0.75rem; border-radius:8px; text-decoration:none; margin-bottom:0.2rem; font-size:0.875rem; font-weight:600; transition: background-color 0.15s ease;
                        color: {{ $link['active'] ? 'var(--cc-mustard)' : 'rgba(247,244,236,0.8)' }};
                        background: {{ $link['active'] ? 'rgba(226,166,59,0.14)' : 'transparent' }};">
                    <span>{{ $link['icon'] }}</span> {{ $link['label'] }}
                </a>
            @endforeach

            <a href="{{ route('chatbot.index') }}" @click="sidebarOpen = false" style="display:flex; align-items:center; gap:0.6rem; padding:0.6rem 0.75rem; border-radius:8px; text-decoration:none; margin-top:0.5rem; font-size:0.875rem; font-weight:600; color: rgba(247,244,236,0.55);">
                <span>💬</span> Full Assistant page
            </a>
        </nav>

        <div style="padding:0.9rem 1.1rem; border-top:1px solid rgba(247,244,236,0.12);" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = ! open" style="background:none; border:none; cursor:pointer; color: rgba(247,244,236,0.85); font-size:0.8125rem; display:flex; align-items:center; justify-content:space-between; width:100%;">
                <span>{{ Auth::user()->name }} <span style="color: var(--cc-mustard); font-size:0.7rem; text-transform:capitalize;">({{ Auth::user()->role }})</span></span>
            </button>
            <div x-show="open" x-transition style="display:none; margin-top:0.5rem;">
                <a href="{{ route('profile.edit') }}" style="display:block; padding:0.3rem 0; font-size:0.8125rem; color: rgba(247,244,236,0.7); text-decoration:none;">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                       style="display:block; padding:0.3rem 0; font-size:0.8125rem; color: var(--cc-clay); text-decoration:none;">Log Out</a>
                </form>
            </div>
        </div>
    </div>
</aside>
