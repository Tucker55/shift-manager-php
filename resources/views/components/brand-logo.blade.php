@props(['variant' => 'dark', 'size' => 'md'])

@php
    $sizes = match ($size) {
        'sm' => ['icon' => 'h-9 w-9', 'name' => 'text-base', 'tag' => 'text-[10px]'],
        'lg' => ['icon' => 'h-14 w-14', 'name' => 'text-3xl', 'tag' => 'text-sm'],
        default => ['icon' => 'h-10 w-10', 'name' => 'text-lg', 'tag' => 'text-xs'],
    };
    $light = $variant === 'light';
@endphp

<a {{ $attributes->merge(['class' => 'inline-flex items-center gap-3']) }}>
    <x-application-logo class="{{ $sizes['icon'] }} {{ $light ? 'text-amber-200' : 'text-amber-800' }}" />
    <span class="flex flex-col leading-none">
        <span class="{{ $sizes['name'] }} font-extrabold tracking-tight {{ $light ? 'text-white' : '' }}" style="{{ $light ? '' : 'color: var(--coffee-950)' }}">Bean & Brew</span>
        <span class="{{ $sizes['tag'] }} font-medium mt-1 {{ $light ? 'text-amber-200/60' : 'opacity-50' }}">Staff rota</span>
    </span>
</a>
