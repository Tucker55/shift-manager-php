@props(['role'])

@php
    $label = match ($role) {
        'barista', 'employee' => 'Barista',
        'manager' => 'Manager',
        default => ucfirst($role),
    };
    $colors = $role === 'manager'
        ? 'bg-purple-100 text-purple-800'
        : 'bg-amber-100 text-amber-900';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold {$colors}"]) }}>
    {{ $label }}
</span>
