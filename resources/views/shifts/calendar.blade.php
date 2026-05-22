<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Team calendar</h2>
                <p class="text-sm text-gray-500 mt-1">Everyone's shifts — not only yours</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('shifts.calendar', ['week' => $prevWeek, 'view' => $view]) }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                    ← Prev
                </a>
                <span class="text-sm font-medium text-gray-700 px-2">
                    {{ $weekStart->format('j M') }} – {{ $weekEnd->format('j M Y') }}
                </span>
                <a href="{{ route('shifts.calendar', ['week' => $nextWeek, 'view' => $view]) }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
                    Next →
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <x-info-banner
                title="Calendar vs My shifts"
                body="This page shows all shifts for your team ({{ $teamShiftsThisWeek }} this week). You have booked {{ $myShiftsThisWeek }}. Only shifts you book appear on My shifts — go to Available to pick one up."
            />

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('shifts.calendar', ['week' => $weekStart->toDateString(), 'view' => 'team']) }}"
                   class="rounded-md px-4 py-2 text-sm font-medium {{ $view === 'team' ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                    All team ({{ $teamShiftsThisWeek }})
                </a>
                <a href="{{ route('shifts.calendar', ['week' => $weekStart->toDateString(), 'view' => 'mine']) }}"
                   class="rounded-md px-4 py-2 text-sm font-medium {{ $view === 'mine' ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                    Only mine ({{ $myShiftsThisWeek }})
                </a>
            </div>

            <div class="flex flex-wrap gap-4 text-xs text-gray-600">
                <span class="flex items-center gap-1"><span class="h-3 w-3 rounded bg-indigo-200 border border-indigo-400"></span> You booked this</span>
                <span class="flex items-center gap-1"><span class="h-3 w-3 rounded bg-white border border-gray-300"></span> Someone else / not booked by you</span>
                <span class="flex items-center gap-1"><span class="h-3 w-3 rounded bg-green-100 border border-green-300"></span> Open slot — you can book</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-7 gap-3">
                @foreach ($days as $day)
                    @php
                        $dateKey = $day->format('Y-m-d');
                        $dayShifts = $shiftsByDate->get($dateKey, collect());
                        $isToday = $day->isToday();
                    @endphp
                    <div class="min-h-[140px] rounded-lg border {{ $isToday ? 'border-indigo-400 bg-indigo-50/30' : 'border-gray-200 bg-white' }} shadow-sm overflow-hidden">
                        <div class="px-3 py-2 border-b {{ $isToday ? 'border-indigo-200 bg-indigo-100/50' : 'border-gray-100 bg-gray-50' }}">
                            <p class="text-xs font-medium text-gray-500 uppercase">{{ $day->format('D') }}</p>
                            <p class="text-lg font-semibold {{ $isToday ? 'text-indigo-700' : 'text-gray-900' }}">{{ $day->format('j') }}</p>
                        </div>
                        <div class="p-2 space-y-2">
                            @forelse ($dayShifts as $shift)
                                @php
                                    $isMine = $shift->isClaimedBy($user);
                                    $isOpen = $shift->hasOpenSlot() && $shift->starts_at->isFuture();
                                    $cardClass = $isMine
                                        ? 'bg-indigo-100 border-indigo-300'
                                        : ($isOpen ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200');
                                @endphp
                                <div class="rounded border px-2 py-1.5 text-xs {{ $cardClass }}">
                                    <p class="font-semibold text-gray-900 truncate">{{ $shift->title }}</p>
                                    <p class="text-gray-600">{{ $shift->starts_at->format('H:i') }}–{{ $shift->ends_at->format('H:i') }}</p>
                                    @if ($isMine)
                                        <p class="text-indigo-700 font-medium mt-0.5">Your shift</p>
                                    @elseif ($isOpen)
                                        <p class="text-green-700 mt-0.5">Open</p>
                                    @else
                                        <p class="text-gray-500 mt-0.5">{{ $shift->filledCount() }}/{{ $shift->slots }} filled</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 text-center py-4">—</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($shiftsByDate->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <x-empty-state
                        :title="$view === 'mine' ? 'You have no shifts this week' : 'Quiet week'"
                        :description="$view === 'mine' ? 'Book a shift from Available and it will show here and on My shifts.' : 'No team shifts scheduled for this week.'"
                    >
                        @if ($view === 'mine')
                            <x-slot name="action">
                                <a href="{{ route('shifts.available') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                    Browse available shifts
                                </a>
                            </x-slot>
                        @endif
                    </x-empty-state>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
