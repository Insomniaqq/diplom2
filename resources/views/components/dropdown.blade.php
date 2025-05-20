@props(['align' => 'left', 'width' => '48'])

@php
    $alignmentClasses = [
        'left' => 'origin-top-left left-0',
        'right' => 'origin-top-right right-0',
    ];
    $widthClass = [
        '48' => 'w-48',
    ][$width] ?? 'w-48';
@endphp

<div class="relative group">
    <div>
        {{ $trigger }}
    </div>
    <div class="absolute z-50 mt-2 rounded-md shadow-lg {{ $alignmentClasses[$align] ?? $alignmentClasses['left'] }} {{ $widthClass }} hidden group-hover:block group-focus:block bg-white">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 bg-white">
            {{ $content }}
        </div>
    </div>
</div> 