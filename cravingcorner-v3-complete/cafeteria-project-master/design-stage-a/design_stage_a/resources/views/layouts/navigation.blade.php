@php
    $isAdmin = auth()->user()->isAdmin();

    $links = $isAdmin ? [
        ['href' => route('dashboard'), 'label' => 'Dashboard', 'active' => request()->routeIs('dashboard')],
        ['href' => route('admin.categories.index'), 'label' => 'Categories', 'active' => request()->routeIs('admin.categories.*')],
        ['href' => route('admin.food-items.index'), 'label' => 'Food Items', 'active' => request()->routeIs('admin.food-items.*')],
        ['href' => route('admin.beverages.index'), 'label' => 'Beverages', 'active' => request()->routeIs('admin.beverages.*')],
        ['href' => route('admin.orders.index'), 'label' => 'Orders', 'active' => request()->routeIs('admin.orders.*')],
        ['href' => route('admin.users.index'), 'label' => 'Users', 'active' => request()->routeIs('admin.users.*')],
        ['href' => route('admin.statistics.index'), 'label' => 'Statistics', 'active' => request()->routeIs('admin.statistics.*')],
    ] : [
        ['href' => route('dashboard'), 'label' => 'Dashboard', 'active' => request()->routeIs('dashboard')],
        ['href' => route('menu.index'), 'label' => 'Menu', 'active' => request()->routeIs('menu.*')],
        ['href' => route('cart.index'), 'label' => 'Cart', 'active' => request()->routeIs('cart.*')],
        ['href' => route('orders.index'), 'label' => 'My Orders', 'active' => request()->routeIs('orders.*')],
        ['href' => route('favorites.index'), 'label' => 'Favorites', 'active' => request()->routeIs('favorites.*')],
        ['href' => route('preferences.edit'), 'label' => 'Preferences', 'active' => request()->routeIs('preferences.*')],
        ['href' => route('surprise-me'), 'label' => 'Surprise Me', 'active' => false],
    ];

    $links[] = ['href' => route('chatbot.index'), 'label' => 'Assistant', 'active' => request()->routeIs('chatbot.*')];
@endphp

<nav x-data="{ open: false }" class="nav-cc" x-cloak>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-10">
                <a href="{{ route('dashboard') }}" class="brand-mark" style="display:flex; align-items:center; gap:0.5rem;">
                    <x-application-logo style="width:1.9rem; height:1.9rem; fill: var(--cc-mustard);" />
                    CravingCorner
                </a>

                <div class="hidden sm:flex items-center gap-6">
                    @foreach ($links as $link)
                        <a href="{{ $link['href'] }}" class="nav-link-cc {{ $link['active'] ? 'active' : '' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center">
                <div x-data="{ open: false }" @click.outside="open = false" style="position:relative;">
                    <button @click="open = ! open" style="background:none; border:none; cursor:pointer; color: rgba(247,244,236,0.85); font-size:0.875rem; display:flex; align-items:center; gap:0.3rem;">
                        {{ Auth::user()->name }}
                        <span style="font-size:0.7rem; color: var(--cc-mustard); text-transform:capitalize;">({{ Auth::user()->role }})</span>
                    </button>

                    <div x-show="open" x-transition style="display:none; position:absolute; right:0; margin-top:0.5rem; width:12rem; background: var(--cc-surface); border:1px solid var(--cc-line); border-radius:8px; overflow:hidden; z-index:50;">
                        <a href="{{ route('profile.edit') }}" style="display:block; padding:0.6rem 1rem; font-size:0.875rem; color: var(--cc-ink); text-decoration:none;">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                               style="display:block; padding:0.6rem 1rem; font-size:0.875rem; color: var(--cc-clay); text-decoration:none;">Log Out</a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="sm:hidden">
                <button @click="open = ! open" style="background:none; border:none; cursor:pointer; color: var(--cc-paper);">
                    <svg style="width:1.5rem; height:1.5rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" x-transition class="sm:hidden" style="display:none; border-top: 1px solid rgba(247,244,236,0.15);">
        <div style="padding: 0.5rem 1rem 1rem;">
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}" @click="open = false"
                   style="display:block; padding:0.6rem 0; color: {{ $link['active'] ? 'var(--cc-mustard)' : 'rgba(247,244,236,0.85)' }}; text-decoration:none; font-weight:600; font-size:0.9rem;">
                    {{ $link['label'] }}
                </a>
            @endforeach

            <div style="border-top:1px solid rgba(247,244,236,0.15); margin-top:0.5rem; padding-top:0.75rem;">
                <p style="color: var(--cc-paper); font-size:0.875rem; margin-bottom:0.25rem;">{{ Auth::user()->name }}</p>
                <p style="color: rgba(247,244,236,0.6); font-size:0.75rem; margin-bottom:0.75rem;">{{ Auth::user()->email }}</p>
                <a href="{{ route('profile.edit') }}" style="display:block; padding:0.4rem 0; color: rgba(247,244,236,0.85); text-decoration:none; font-size:0.875rem;">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                       style="display:block; padding:0.4rem 0; color: var(--cc-clay); text-decoration:none; font-size:0.875rem;">Log Out</a>
                </form>
            </div>
        </div>
    </div>
</nav>
