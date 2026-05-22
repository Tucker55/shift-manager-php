@props(['shift', 'user' => null, 'compact' => false])

@php $user = $user ?? auth()->user(); @endphp

<div {{ $attributes->merge(['class' => 'card card-accent p-6 lg:p-7']) }}>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-3 mb-3">
                <h4 class="text-lg font-bold" style="color: var(--coffee-950)">{{ $shift->title }}</h4>
                <x-shift-status-badge :shift="$shift" :user="$user" />
            </div>
            <div class="flex flex-wrap gap-2 mb-2">
                <span class="time-chip">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $shift->starts_at->format('D j M · H:i') }} – {{ $shift->ends_at->format('H:i') }}
                </span>
                <span class="time-chip">{{ $shift->durationHours() }}h</span>
            </div>
            @if ($shift->location)
                <p class="text-sm" style="color: #6b5344">📍 {{ $shift->location }}</p>
            @endif
            @unless ($compact)
                @if ($shift->notes)
                    <p class="mt-3 text-sm italic opacity-70">{{ $shift->notes }}</p>
                @endif
                <p class="mt-2 text-xs font-medium uppercase tracking-wide opacity-50">
                    {{ $shift->filledCount() }} / {{ $shift->slots }} baristas
                </p>
            @endunless
        </div>
        @if (isset($actions))
            <div class="shrink-0">{{ $actions }}</div>
        @endif
    </div>
</div>
