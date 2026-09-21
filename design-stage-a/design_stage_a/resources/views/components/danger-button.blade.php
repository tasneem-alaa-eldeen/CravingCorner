<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-ink']) }} style="background-color: var(--cc-clay);">
    {{ $slot }}
</button>
