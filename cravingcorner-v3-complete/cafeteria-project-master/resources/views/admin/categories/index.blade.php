<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display" style="font-size:1.4rem; font-weight:700; color: var(--cc-ink);">Categories</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="badge badge-sage" style="display:block; padding:0.6rem 1rem; margin-bottom:1rem;">{{ session('status') }}</div>
        @endif

        <div style="display:flex; justify-content:flex-end; margin-bottom:1rem;">
            <a href="{{ route('admin.categories.create') }}" class="btn-mustard">+ New Category</a>
        </div>

        <div class="card-plain" style="overflow:hidden;">
            <table style="width:100%; text-align:left; font-size:0.875rem; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--cc-line); text-transform:uppercase; font-size:0.7rem; color: var(--cc-text-muted);">
                        <th style="padding:0.75rem 1.1rem;">Photo</th>
                        <th style="padding:0.75rem 1.1rem;">Name</th>
                        <th style="padding:0.75rem 1.1rem;">Type</th>
                        <th style="padding:0.75rem 1.1rem;"># Food</th>
                        <th style="padding:0.75rem 1.1rem;"># Beverages</th>
                        <th style="padding:0.75rem 1.1rem; text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr style="border-bottom:1px solid var(--cc-line);">
                            <td style="padding:0.75rem 1.1rem;">
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" style="width:44px; height:44px; object-fit:cover; border-radius:6px;">
                                @else
                                    <div style="width:44px; height:44px; border-radius:6px; background: var(--cc-paper); border:1px dashed var(--cc-line); display:flex; align-items:center; justify-content:center; font-size:1.1rem;">🏷️</div>
                                @endif
                            </td>
                            <td style="padding:0.75rem 1.1rem;">{{ $category->name }}</td>
                            <td style="padding:0.75rem 1.1rem;"><span class="badge badge-mustard">{{ $category->type }}</span></td>
                            <td style="padding:0.75rem 1.1rem;">{{ $category->food_items_count }}</td>
                            <td style="padding:0.75rem 1.1rem;">{{ $category->beverages_count }}</td>
                            <td style="padding:0.75rem 1.1rem; text-align:right; white-space:nowrap;">
                                <a href="{{ route('admin.categories.edit', $category) }}" style="color: var(--cc-mustard-dark); font-weight:600; font-size:0.8125rem; text-decoration:none; margin-right:0.75rem;">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-text-clay">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="padding:2rem; text-align:center; color: var(--cc-text-muted);">No categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
