<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Shift history</h2>
                <p class="text-sm text-gray-500 mt-1">A record of all completed shifts</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('shifts.history', ['month' => $prevMonth]) }}"
                   class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                    ← Prev
                </a>
                <span class="text-sm font-semibold text-gray-800 px-2 min-w-[120px] text-center">
                    {{ $month->format('F Y') }}
                </span>
                @if ($month->copy()->addMonth()->startOfMonth()->isPast())
                    <a href="{{ route('shifts.history', ['month' => $nextMonth]) }}"
                       class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                        Next →
                    </a>
                @else
                    <span class="rounded-md border border-gray-200 px-3 py-1.5 text-sm text-gray-300 cursor-not-allowed">
                        Next →
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary banner --}}
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100">
                        <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-amber-900">{{ $month->format('F Y') }}</p>
                        <p class="text-xs text-amber-700">
                            You worked <strong>{{ $totalWorked }}</strong> shift{{ $totalWorked !== 1 ? 's' : '' }} this month
                            &middot; {{ $shiftsByDate->flatten()->count() }} total team shifts completed
                        </p>
                    </div>
                </div>
                <a href="{{ route('shifts.mine') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-700 hover:text-amber-900 underline underline-offset-2">
                    View my upcoming shifts →
                </a>
            </div>

            {{-- Legend --}}
            <div class="flex flex-wrap gap-4 text-xs text-gray-600 px-1">
                <span class="flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded bg-amber-200 border border-amber-400 inline-block"></span>
                    You worked this shift
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded bg-gray-100 border border-gray-300 inline-block"></span>
                    Team shift (you weren't on it)
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded bg-white border border-dashed border-gray-300 inline-block"></span>
                    No shifts
                </span>
            </div>

            {{-- Calendar grid --}}
            @php
                $startOfCalendar = $month->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::MONDAY);
                $endOfCalendar   = $month->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SUNDAY);
                $calendarDays    = collect();
                $cursor          = $startOfCalendar->copy();
                while ($cursor->lte($endOfCalendar)) {
                    $calendarDays->push($cursor->copy());
                    $cursor->addDay();
                }
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                {{-- Day headers --}}
                <div class="grid grid-cols-7 border-b border-gray-200">
                    @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $dayName)
                        <div class="py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide {{ $loop->index >= 5 ? 'bg-gray-50' : '' }}">
                            {{ $dayName }}
                        </div>
                    @endforeach
                </div>

                {{-- Day cells --}}
                <div class="grid grid-cols-7">
                    @foreach ($calendarDays as $day)
                        @php
                            $dateKey    = $day->format('Y-m-d');
                            $dayShifts  = $shiftsByDate->get($dateKey, collect());
                            $inMonth    = $day->month === $month->month;
                            $isWeekend  = $day->isWeekend();
                            $isToday    = $day->isToday();
                        @endphp
                        <div class="min-h-[100px] border-b border-r border-gray-100
                            {{ ! $inMonth ? 'bg-gray-50/60' : ($isWeekend ? 'bg-orange-50/30' : 'bg-white') }}
                            {{ $isToday ? 'ring-2 ring-inset ring-amber-400' : '' }}
                            p-1.5">

                            {{-- Date number --}}
                            <div class="flex justify-end mb-1">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xs font-semibold
                                    {{ $isToday ? 'bg-amber-500 text-white' : ($inMonth ? 'text-gray-700' : 'text-gray-300') }}">
                                    {{ $day->format('j') }}
                                </span>
                            </div>

                            {{-- Shifts for this day --}}
                            <div class="space-y-1">
                                @foreach ($dayShifts as $shift)
                                    @php $workedIt = $shift->isClaimedBy($user); @endphp
                                    <div class="rounded px-1.5 py-1 text-xs leading-tight
                                        {{ $workedIt
                                            ? 'bg-amber-100 border border-amber-300'
                                            : 'bg-gray-100 border border-gray-200' }}">
                                        <p class="font-semibold truncate {{ $workedIt ? 'text-amber-900' : 'text-gray-600' }}">
                                            {{ $shift->title }}
                                        </p>
                                        <p class="{{ $workedIt ? 'text-amber-700' : 'text-gray-500' }}">
                                            {{ $shift->starts_at->format('H:i') }}–{{ $shift->ends_at->format('H:i') }}
                                        </p>
                                        @if ($workedIt)
                                            <p class="text-amber-600 font-medium mt-0.5">✓ You worked this</p>
                                        @else
                                            <p class="text-gray-400 mt-0.5">{{ $shift->filledCount() }}/{{ $shift->slots }} staff</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Monthly list of MY past shifts --}}
            @if ($myPastShifts->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-amber-50">
                        <h3 class="text-sm font-semibold text-amber-900">Your shifts this month</h3>
                        <p class="text-xs text-amber-700 mt-0.5">Every shift you completed in {{ $month->format('F') }}</p>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        @foreach ($myPastShifts->sortBy('starts_at') as $shift)
                            <li class="flex items-center justify-between px-6 py-3 hover:bg-gray-50">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-100 text-amber-700 font-bold text-xs shrink-0">
                                        {{ $shift->starts_at->format('d') }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $shift->title }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $shift->starts_at->format('l, j F') }}
                                            &middot; {{ $shift->starts_at->format('H:i') }}–{{ $shift->ends_at->format('H:i') }}
                                            @if ($shift->location)
                                                &middot; {{ $shift->location }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                    ✓ Completed
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="rounded-xl border border-gray-200 bg-white px-6 py-10 text-center">
                    <p class="text-sm font-medium text-gray-500">No shifts worked in {{ $month->format('F Y') }}</p>
                    <p class="text-xs text-gray-400 mt-1">Try navigating to a different month using the arrows above.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
