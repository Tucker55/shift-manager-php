@props(['title', 'body'])

<div {{ $attributes->merge(['class' => 'rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900']) }}>
    <p class="font-semibold">{{ $title }}</p>
    <p class="mt-1 text-blue-800">{{ $body }}</p>
</div>
