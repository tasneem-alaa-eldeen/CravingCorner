@php
    $item = $row['item'];
    $type = $row['type'];
    $match = $row['match'];
    $showRoute = $type === 'food' ? route('menu.food.show', $item) : route('menu.beverage.show', $item);

    // Ring color scales with confidence.
    $ringColor = $match >= 75 ? 'var(--cc-sage)' : ($match >= 50 ? 'var(--cc-mustard)' : 'var(--cc-clay)');
@endphp

<div class="card-ticket" style="padding:1rem; position:relative;">

    <!-- Match % ring, pure CSS conic-gradient (no per-card chart instance needed) -->
    <div style="position:absolute; top:10px; right:10px; width:46px; height:46px; border-radius:999px;
                background: conic-gradient({{ $ringColor }} {{ $match * 3.6 }}deg, var(--cc-line) 0deg);
                display:flex; align-items:center; justify-content:center;">
        <div style="width:36px; height:36px; border-radius:999px; background: var(--cc-surface); display:flex; align-items:center; justify-content:center; font-size:0.68rem; font-weight:700; color: var(--cc-ink);">
            {{ $match }}%
        </div>
    </div>

    @if ($item->image)
        <img src="{{ asset('storage/' . $item->image) }}" style="width:100%; height:120px; object-fit:contain; border-radius:8px; margin-bottom:0.6rem; background: var(--cc-paper); border:1px solid var(--cc-line); padding:0.4rem;" alt="{{ $item->name }}">
    @else
        <div style="width:100%; height:120px; border-radius:8px; margin-bottom:0.6rem; background: var(--cc-paper); border:1px dashed var(--cc-line); display:flex; align-items:center; justify-content:center; font-size:2rem;">
            {{ $type === 'food' ? '🍽️' : '🥤' }}
        </div>
    @endif

    <a href="{{ $showRoute }}" style="font-weight:700; color: var(--cc-ink); text-decoration:none; padding-right:2.2rem; display:block;">{{ $item->name }}</a>
    <p style="font-size:0.75rem; color: var(--cc-text-muted); margin-top:0.2rem;">
        {{ $item->category->name ?? '' }} @if($item->calories) · {{ $item->calories }} cal @endif
    </p>

    <p class="badge {{ $match >= 75 ? 'badge-sage' : ($match >= 50 ? 'badge-mustard' : 'badge-clay') }}" style="margin-top:0.5rem;">
        {{ $match }}% match
    </p>

    <p class="font-mono" style="margin-top:0.5rem; font-weight:700;">{{ number_format($item->price, 2) }} EGP</p>

    <form action="{{ route('cart.add') }}" method="POST" style="margin-top:0.75rem; padding-top:0.75rem; border-top:1px dashed var(--cc-line);">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="id" value="{{ $item->id }}">
        <input type="hidden" name="quantity" value="1">
        <button type="submit" class="btn-mustard" style="width:100%; font-size:0.8125rem;">Add to cart</button>
    </form>
</div>
