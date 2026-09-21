<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">{{ $user->name }}</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="badge badge-sage" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">{{ session('status') }}</div>
        @endif

        <div class="card-plain" style="padding:1.5rem; margin-bottom:1.5rem;">
            <dl style="font-size:0.875rem;">
                <div style="display:flex; justify-content:space-between; padding:0.35rem 0;"><dt style="color: var(--cc-text-muted);">Email</dt><dd>{{ $user->email }}</dd></div>
                <div style="display:flex; justify-content:space-between; padding:0.35rem 0;"><dt style="color: var(--cc-text-muted);">Phone</dt><dd>{{ $user->phone ?? '—' }}</dd></div>
                <div style="display:flex; justify-content:space-between; padding:0.35rem 0;"><dt style="color: var(--cc-text-muted);">Location</dt><dd>{{ $user->location ?? '—' }}</dd></div>
                <div style="display:flex; justify-content:space-between; padding:0.35rem 0;"><dt style="color: var(--cc-text-muted);">Age</dt><dd>{{ $user->age ?? '—' }}</dd></div>
                <div style="display:flex; justify-content:space-between; padding:0.35rem 0;"><dt style="color: var(--cc-text-muted);">Joined</dt><dd>{{ $user->created_at->format('Y-m-d') }}</dd></div>
            </dl>

            <form action="{{ route('admin.users.update', $user) }}" method="POST" style="margin-top:1rem; display:flex; align-items:center; gap:0.75rem;">
                @csrf
                @method('PUT')
                <label style="font-size:0.875rem; color: var(--cc-text-muted);">Role</label>
                <select name="role" class="input-cc">
                    <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <x-primary-button style="padding:0.4rem 1rem; font-size:0.8125rem;">Save</x-primary-button>
            </form>
        </div>

        <div class="card-plain" style="overflow:hidden;">
            <div style="padding:0.75rem 1.1rem; border-bottom:1px solid var(--cc-line); font-weight:700; font-family:'Baloo 2',sans-serif;">Order history</div>
            @forelse ($user->orders as $order)
                <div style="display:flex; justify-content:space-between; padding:0.6rem 1.1rem; border-bottom:1px solid var(--cc-line); font-size:0.875rem;">
                    <span>#{{ $order->id }} — {{ $order->created_at->format('Y-m-d') }}</span>
                    <span><span class="badge badge-mustard">{{ $order->status }}</span> <span class="font-mono">{{ number_format($order->total_price, 2) }} EGP</span></span>
                </div>
            @empty
                <p style="padding:1.25rem; color: var(--cc-text-muted); font-size:0.875rem;">No orders yet.</p>
            @endforelse
        </div>

        <a href="{{ route('admin.users.index') }}" style="display:inline-block; margin-top:1.5rem; font-size:0.875rem; color: var(--cc-text-muted); text-decoration:none;">&larr; Back to users</a>
    </div>
</x-app-layout>
