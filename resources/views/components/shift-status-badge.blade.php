@props(['shift', 'user' => null])

@php
    $label = $shift->availabilityLabel($user);
    $classes = match ($shift->availabilityColor($user)) {
        'green' => 'bg-emerald-100 text-emerald-800',
        'indigo' => 'bg-amber-100 text-amber-900',
        'amber' => 'bg-stone-100 text-stone-600',
        default => 'bg-stone-100 text-stone-500',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex rounded-full px-3 py-1 text-xs font-bold {$classes}"]) }}>
    {{ $label }}
</span>
