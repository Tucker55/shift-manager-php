@props(['label' => null, 'title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'mb-10']) }}>
    @if ($label)
        <p class="label-upper mb-2">{{ $label }}</p>
    @endif
    <h1 class="page-title">{{ $title }}</h1>
    @if ($subtitle)
        <p class="page-subtitle">{{ $subtitle }}</p>
    @endif
</div>
