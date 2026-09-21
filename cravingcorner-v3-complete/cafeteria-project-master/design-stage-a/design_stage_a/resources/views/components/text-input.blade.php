@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'input-cc']) }} style="width:100%;">
