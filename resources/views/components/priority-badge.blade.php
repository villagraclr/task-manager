@props(['priority'])

<x-badge :color="$priority->color()">
    {{ $priority->label() }}
</x-badge>
