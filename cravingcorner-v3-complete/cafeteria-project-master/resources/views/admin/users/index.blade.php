<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">Users</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="badge badge-sage" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">{{ session('status') }}</div>
        @endif

        <div class="card-plain" style="overflow:hidden;">
            <table style="width:100%; text-align:left; font-size:0.875rem; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--cc-line); text-transform:uppercase; font-size:0.7rem; color: var(--cc-text-muted);">
                        <th style="padding:0.75rem 1.1rem;">Name</th>
                        <th style="padding:0.75rem 1.1rem;">Email</th>
                        <th style="padding:0.75rem 1.1rem;">Role</th>
                        <th style="padding:0.75rem 1.1rem;"># Orders</th>
                        <th style="padding:0.75rem 1.1rem; text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr style="border-bottom:1px solid var(--cc-line);">
                            <td style="padding:0.75rem 1.1rem;">{{ $user->name }}</td>
                            <td style="padding:0.75rem 1.1rem;">{{ $user->email }}</td>
                            <td style="padding:0.75rem 1.1rem;"><span class="badge {{ $user->role === 'admin' ? 'badge-mustard' : 'badge-muted' }}">{{ $user->role }}</span></td>
                            <td style="padding:0.75rem 1.1rem;">{{ $user->orders_count }}</td>
                            <td style="padding:0.75rem 1.1rem; text-align:right;">
                                <a href="{{ route('admin.users.show', $user) }}" style="color: var(--cc-mustard-dark); font-weight:600; text-decoration:none;">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding:2rem; text-align:center; color: var(--cc-text-muted);">No users yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
