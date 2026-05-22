@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-amber-300 text-base font-medium text-white bg-amber-800/50'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-base font-medium text-amber-100 hover:text-white hover:bg-amber-800/30 hover:border-amber-500';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
