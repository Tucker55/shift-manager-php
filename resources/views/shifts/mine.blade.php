<x-app-layout>
    <x-page-shell>
        <x-page-header label="Your rota" title="My schedule" subtitle="Only shifts you've signed up for" />
        <x-flash-messages class="mb-8" />

        <section class="mb-14">
            <h2 class="text-xl font-bold mb-6" style="color: var(--coffee-900)">Coming up</h2>
            <div class="space-y-5">
                @forelse ($upcoming as $shift)
                    <x-shift-card :shift="$shift">
                        <x-slot name="actions">
                            @if ($shift->starts_at->isFuture())
                                <form method="POST" action="{{ route('shifts.release', $shift) }}">
                                    @csrf
                                    <button type="submit" class="btn-secondary">Can't make it</button>
                                </form>
                            @endif
                        </x-slot>
                    </x-shift-card>
                @empty
                    <div class="card p-12 text-center">
                        <p class="opacity-70">Nothing booked. <a href="{{ route('shifts.available') }}" class="font-semibold underline" style="color: #8b5a2b">Pick up a shift</a></p>
                    </div>
                @endforelse
            </div>
        </section>

        <section>
            <h2 class="text-xl font-bold mb-6" style="color: var(--coffee-900)">Already worked</h2>
            <div class="space-y-5">
                @forelse ($past as $shift)
                    <x-shift-card :shift="$shift" compact />
                @empty
                    <p class="card p-8 text-center opacity-60">No history yet.</p>
                @endforelse
            </div>
        </section>
    </x-page-shell>
</x-app-layout>
