<x-app-layout>
    <x-page-shell>
        <x-page-header label="Rota" title="Open shifts" subtitle="Sign up for gaps on the schedule" />
        <x-flash-messages class="mb-8" />

        <div class="space-y-5">
            @forelse ($shifts as $shift)
                <x-shift-card :shift="$shift">
                    <x-slot name="actions">
                        <form method="POST" action="{{ route('shifts.claim', $shift) }}">
                            @csrf
                            <button type="submit" class="btn-primary">Sign me up</button>
                        </form>
                    </x-slot>
                </x-shift-card>
            @empty
                <div class="card p-12 text-center">
                    <div class="text-5xl mb-4">✓</div>
                    <h3 class="text-xl font-bold" style="color: var(--coffee-950)">Fully staffed</h3>
                    <p class="mt-2 opacity-70">No open shifts right now. Check back soon.</p>
                </div>
            @endforelse
        </div>
    </x-page-shell>
</x-app-layout>
