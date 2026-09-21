@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => '']) }} style="font-weight:600; font-size:0.875rem; color: var(--cc-sage);">
        {{ $status }}
    </div>
@endif
