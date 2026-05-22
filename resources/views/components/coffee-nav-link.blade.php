@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-amber-300 text-sm font-medium text-white'
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-amber-100 hover:text-white hover:border-amber-400';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
