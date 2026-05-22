<x-app-layout>
    <x-page-shell>
        <x-page-header label="Team" title="Staff" :subtitle="$team ? $team->name.' · '.$team->location : 'Bean & Brew team'" />

        @if ($team)
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($members as $member)
                    <div class="card p-7 {{ $member->id === auth()->id() ? 'ring-2 ring-amber-600/30' : '' }}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-4">
                                <div class="avatar">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                                <div>
                                    <p class="font-bold text-lg" style="color: var(--coffee-950)">{{ $member->name }}</p>
                                    <p class="text-sm mt-1 opacity-60">{{ $member->email }}</p>
                                </div>
                            </div>
                            <x-role-badge :role="$member->role" />
                        </div>
                        @if ($member->id === auth()->id())
                            <p class="mt-4 text-xs font-bold uppercase tracking-wider" style="color: #8b5a2b">You</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="card p-12 text-center opacity-70">Not assigned to a team yet.</div>
        @endif
    </x-page-shell>
</x-app-layout>
