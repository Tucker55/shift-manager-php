@props(['title', 'description' => null])

<div {{ $attributes->merge(['class' => 'text-center py-16 px-6']) }}>
    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
    </div>
    <h3 class="mt-4 text-sm font-semibold text-gray-900">{{ $title }}</h3>
    @if ($description)
        <p class="mt-2 text-sm text-gray-500">{{ $description }}</p>
    @endif
    @if (isset($action))
        <div class="mt-6">{{ $action }}</div>
    @endif
</div>
