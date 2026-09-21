<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-mustard']) }}>
    {{ $slot }}
</button>
