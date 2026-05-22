<x-app-layout>
    <x-page-shell>
        <x-page-header
            label="Manager"
            title="Rota control"
            subtitle="Build the Bean & Brew schedule, edit shifts, and spot gaps before service gets busy"
        />

        <x-flash-messages class="mb-8" />

        @if ($errors->any())
            <div class="mb-8 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
                <p class="font-semibold">Please fix these fields:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-5 sm:grid-cols-4 mb-10">
            <x-stat-card label="Total shifts" :value="$totalShifts" />
            <x-stat-card label="Need cover" :value="$openShifts" />
            <x-stat-card label="Fully covered" :value="$coveredShifts" />
            <x-stat-card label="Staff" :value="$teamSize" :href="route('team.index')" link-text="View staff →" />
        </div>

        <div class="grid gap-8 lg:grid-cols-[420px_1fr]">
            <section class="card p-6 lg:p-8 h-fit">
                <h2 class="text-xl font-bold" style="color: var(--coffee-950)">Add a shift</h2>
                <p class="mt-2 text-sm opacity-70">Create a new open shift for baristas to pick up.</p>

                <form method="POST" action="{{ route('manager.shifts.store') }}" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="title" value="Shift name" />
                        <x-text-input
                            id="title"
                            name="title"
                            class="mt-2 block w-full rounded-xl"
                            value="{{ old('title') }}"
                            placeholder="Morning rush"
                            required
                        />
                    </div>

                    <div>
                        <x-input-label for="location" value="Location" />
                        <x-text-input
                            id="location"
                            name="location"
                            class="mt-2 block w-full rounded-xl"
                            value="{{ old('location', 'Main bar') }}"
                        />
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <x-input-label for="starts_at" value="Starts" />
                            <x-text-input
                                id="starts_at"
                                name="starts_at"
                                type="datetime-local"
                                class="mt-2 block w-full rounded-xl"
                                value="{{ old('starts_at', now()->addDay()->setTime(8, 0)->format('Y-m-d\TH:i')) }}"
                                required
                            />
                        </div>
                        <div>
                            <x-input-label for="ends_at" value="Ends" />
                            <x-text-input
                                id="ends_at"
                                name="ends_at"
                                type="datetime-local"
                                class="mt-2 block w-full rounded-xl"
                                value="{{ old('ends_at', now()->addDay()->setTime(14, 0)->format('Y-m-d\TH:i')) }}"
                                required
                            />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="slots" value="How many staff?" />
                        <x-text-input
                            id="slots"
                            name="slots"
                            type="number"
                            min="1"
                            max="20"
                            class="mt-2 block w-full rounded-xl"
                            value="{{ old('slots', 1) }}"
                            required
                        />
                    </div>

                    <div>
                        <x-input-label for="notes" value="Notes" />
                        <textarea
                            id="notes"
                            name="notes"
                            rows="4"
                            class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                            placeholder="Dial in grinders, stock cups, prep pastries"
                        >{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        Add shift
                    </button>
                </form>
            </section>

            <section>
                <div class="mb-6">
                    <h2 class="text-xl font-bold" style="color: var(--coffee-950)">Current rota</h2>
                    <p class="mt-2 text-sm opacity-70">Edit shift details or remove mistakes from the schedule.</p>
                </div>

                <div class="space-y-5">
                    @forelse ($shifts as $shift)
                        <div class="card card-accent p-6 lg:p-7">
                            <div class="flex flex-col xl:flex-row xl:items-start justify-between gap-5">
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-3 mb-3">
                                        <h3 class="text-lg font-bold" style="color: var(--coffee-950)">{{ $shift->title }}</h3>
                                        <x-shift-status-badge :shift="$shift" />
                                    </div>
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        <span class="time-chip">{{ $shift->starts_at->format('D j M · H:i') }} – {{ $shift->ends_at->format('H:i') }}</span>
                                        <span class="time-chip">{{ $shift->durationHours() }}h</span>
                                        <span class="time-chip">{{ $shift->filledCount() }}/{{ $shift->slots }} staff</span>
                                    </div>
                                    <p class="text-sm opacity-70">📍 {{ $shift->location ?: 'No location set' }}</p>
                                    @if ($shift->notes)
                                        <p class="mt-3 text-sm italic opacity-60">{{ $shift->notes }}</p>
                                    @endif
                                </div>

                                <div class="flex gap-2">
                                    <details class="relative">
                                        <summary class="btn-secondary cursor-pointer list-none">Edit</summary>
                                        <div class="absolute right-0 z-20 mt-3 w-[min(34rem,calc(100vw-2rem))] card p-5">
                                            <form method="POST" action="{{ route('manager.shifts.update', $shift) }}" class="space-y-4">
                                                @csrf
                                                @method('PUT')

                                                <div class="grid gap-4 sm:grid-cols-2">
                                                    <div class="sm:col-span-2">
                                                        <x-input-label value="Shift name" />
                                                        <x-text-input name="title" class="mt-2 block w-full rounded-xl" value="{{ old('title', $shift->title) }}" required />
                                                    </div>
                                                    <div>
                                                        <x-input-label value="Location" />
                                                        <x-text-input name="location" class="mt-2 block w-full rounded-xl" value="{{ old('location', $shift->location) }}" />
                                                    </div>
                                                    <div>
                                                        <x-input-label value="Staff needed" />
                                                        <x-text-input name="slots" type="number" min="1" max="20" class="mt-2 block w-full rounded-xl" value="{{ old('slots', $shift->slots) }}" required />
                                                    </div>
                                                    <div>
                                                        <x-input-label value="Starts" />
                                                        <x-text-input name="starts_at" type="datetime-local" class="mt-2 block w-full rounded-xl" value="{{ old('starts_at', $shift->starts_at->format('Y-m-d\TH:i')) }}" required />
                                                    </div>
                                                    <div>
                                                        <x-input-label value="Ends" />
                                                        <x-text-input name="ends_at" type="datetime-local" class="mt-2 block w-full rounded-xl" value="{{ old('ends_at', $shift->ends_at->format('Y-m-d\TH:i')) }}" required />
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <x-input-label value="Notes" />
                                                        <textarea name="notes" rows="3" class="mt-2 block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">{{ old('notes', $shift->notes) }}</textarea>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn-primary w-full">Save changes</button>
                                            </form>
                                        </div>
                                    </details>

                                    <form method="POST" action="{{ route('manager.shifts.destroy', $shift) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn-secondary"
                                            onclick="return confirm('Remove this shift from the rota?')"
                                        >
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card p-12 text-center">
                            <p class="opacity-70">No shifts on the rota yet.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </x-page-shell>
</x-app-layout>
