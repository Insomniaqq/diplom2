@props([
    'disabled' => false,
    'type' => 'text',
])
<input
    {{ $attributes->merge([
        'class' => 'form-input',
        'type' => $type,
    ]) }}
    @if($disabled) disabled @endif
/> 