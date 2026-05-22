@props(['label', 'value', 'href' => null, 'linkText' => 'View →'])

<div class="stat-card">
    <p class="label-upper">{{ $label }}</p>
    <p class="text-4xl lg:text-5xl font-bold mt-3" style="color: var(--coffee-900)">{{ $value }}</p>
    @if ($href)
        <a href="{{ $href }}" class="inline-block mt-4 text-sm font-semibold hover:underline" style="color: #8b5a2b">{{ $linkText }}</a>
    @endif
</div>
