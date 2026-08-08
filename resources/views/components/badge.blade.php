@props(['color' => 'gray'])

@php
$colorClasses = match ($color) {
    'gray' => 'bg-gray-100 text-gray-800',
    'blue' => 'bg-blue-100 text-blue-800',
    'orange' => 'bg-orange-100 text-orange-800',
    'red' => 'bg-red-100 text-red-800',
    'green' => 'bg-green-100 text-green-800',
    default => 'bg-gray-100 text-gray-800',
};
@endphp

<span {{ $attributes->merge(['class' => "rounded px-2 py-1 text-xs font-medium $colorClasses"]) }}>
    {{ $slot }}
</span>
