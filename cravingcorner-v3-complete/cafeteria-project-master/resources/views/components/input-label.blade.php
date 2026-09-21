@props(['value'])

<label {{ $attributes->merge(['class' => '']) }} style="display:block; font-weight:600; font-size:0.8125rem; color: var(--cc-text-muted); margin-bottom:0.25rem;">
    {{ $value ?? $slot }}
</label>
