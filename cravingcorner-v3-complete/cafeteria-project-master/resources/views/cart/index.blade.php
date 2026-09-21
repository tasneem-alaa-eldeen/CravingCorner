<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">Your Cart</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="badge badge-sage" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">{{ session('status') }}</div>
        @endif

        <div class="card-plain" style="overflow:hidden;">
            @forelse ($cart as $line)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:0.9rem 1.25rem; border-bottom:1px solid var(--cc-line);">
                    <div>
                        <p style="font-size:0.9rem; color: var(--cc-ink);">{{ $line['item']->name }}</p>
                        <p style="font-size:0.75rem; color: var(--cc-text-muted);">{{ $line['quantity'] }} &times; {{ number_format($line['item']->price, 2) }} EGP</p>
                    </div>
                    <div style="display:flex; align-items:center; gap:1rem;">
                        <p class="font-mono" style="font-weight:600;">{{ number_format($line['subtotal'], 2) }} EGP</p>
                        <form action="{{ route('cart.remove', $line['key']) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-text-clay">Remove</button>
                        </form>
                    </div>
                </div>
            @empty
                <p style="padding:2rem; text-align:center; color: var(--cc-text-muted); font-size:0.875rem;">Your cart is empty.</p>
            @endforelse
        </div>

        @if (! empty($cart))
            <div style="margin-top:1rem; display:flex; align-items:center; justify-content:space-between;">
                <p class="font-mono" style="font-weight:700; font-size:1.1rem;">Total: {{ number_format(collect($cart)->sum('subtotal'), 2) }} EGP</p>
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <x-primary-button>Place order</x-primary-button>
                </form>
            </div>
        @endif

        <a href="{{ route('menu.index') }}" style="display:inline-block; margin-top:1.5rem; font-size:0.875rem; color: var(--cc-text-muted); text-decoration:none;">&larr; Back to menu</a>
    </div>
</x-app-layout>
