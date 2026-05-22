<x-app-layout>
    <x-page-shell>
        <div class="hero-banner px-8 py-10 lg:px-12 lg:py-12 text-white mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="avatar w-16 h-16 text-2xl rounded-2xl" style="background: linear-gradient(135deg, #c4956a, #f0e8dc); color: #2d1810">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-amber-200/80 text-sm font-medium uppercase tracking-widest">Welcome back</p>
                        <h1 class="text-3xl lg:text-4xl font-bold mt-1">{{ $user->name }}</h1>
                        <p class="text-amber-100/70 mt-2">{{ $user->team?->name }} · {{ $user->team?->location }}</p>
                    </div>
                </div>
                @if ($openShiftsCount > 0)
                    <a href="{{ route('shifts.available') }}" class="inline-flex items-center justify-center rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 px-6 py-3 font-semibold text-white transition backdrop-blur">
                        {{ $openShiftsCount }} open {{ Str::plural('shift', $openShiftsCount) }} →
                    </a>
                @endif
            </div>
        </div>

        <x-flash-messages class="mb-8" />

        <div class="grid gap-6 sm:grid-cols-2 mb-12">
            <x-stat-card label="Your shifts" :value="$myUpcoming->count()" :href="route('shifts.mine')" link-text="My schedule →" />
            <x-stat-card label="Team size" :value="$teamSize" :href="route('team.index')" link-text="View staff →" />
        </div>

        <x-page-header label="Schedule" title="Your next shifts" subtitle="Shifts you've signed up for" class="!mb-8" />

        <div class="space-y-5">
            @forelse ($myUpcoming as $shift)
                <x-shift-card :shift="$shift" :user="$user" />
            @empty
                <div class="card p-12 text-center">
                    <div class="text-5xl mb-4">☕</div>
                    <h3 class="text-xl font-bold" style="color: var(--coffee-950)">No shifts yet</h3>
                    <p class="mt-2 opacity-70 max-w-sm mx-auto">Grab an open slot on the board when you're ready to work.</p>
                    <a href="{{ route('shifts.available') }}" class="btn-primary mt-8">Browse open shifts</a>
                </div>
            @endforelse
        </div>
    </x-page-shell>
</x-app-layout>
