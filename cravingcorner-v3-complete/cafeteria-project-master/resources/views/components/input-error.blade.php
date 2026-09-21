@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => '']) }} style="font-size:0.8125rem; color: var(--cc-clay); margin-top:0.25rem;">
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
