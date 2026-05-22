<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftController extends Controller
{
    public function manage(Request $request): View
    {
        $user = $request->user();

        abort_unless($user->isManager(), 403);

        $shifts = Shift::query()
            ->forTeam($user->team_id)
            ->with('workers')
            ->orderBy('starts_at')
            ->get();

        return view('manager.shifts', [
            'shifts' => $shifts,
            'totalShifts' => $shifts->count(),
            'openShifts' => $shifts
                ->filter(fn (Shift $shift) => $shift->starts_at->isFuture() && $shift->hasOpenSlot())
                ->count(),
            'coveredShifts' => $shifts
                ->filter(fn (Shift $shift) => ! $shift->hasOpenSlot())
                ->count(),
            'teamSize' => $user->team?->users()->count() ?? 0,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->isManager(), 403);

        $validated = $this->validateShift($request);

        Shift::create([
            ...$validated,
            'team_id' => $user->team_id,
        ]);

        return back()->with('status', 'Shift added to the rota.');
    }

    public function update(Request $request, Shift $shift): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->isManager(), 403);

        if ($shift->team_id !== $user->team_id) {
            abort(403);
        }

        $shift->update($this->validateShift($request));

        return back()->with('status', 'Shift updated.');
    }

    public function destroy(Request $request, Shift $shift): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->isManager(), 403);

        if ($shift->team_id !== $user->team_id) {
            abort(403);
        }

        $shift->delete();

        return back()->with('status', 'Shift removed from the rota.');
    }

    private function validateShift(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'slots' => ['required', 'integer', 'min:1', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    public function available(Request $request): View
    {
        $user = $request->user();

        $shifts = Shift::query()
            ->forTeam($user->team_id)
            ->upcoming()
            ->with(['team', 'workers'])
            ->orderBy('starts_at')
            ->get()
            ->filter(fn (Shift $shift) => $shift->hasOpenSlot() && ! $shift->isClaimedBy($user));

        return view('shifts.available', [
            'shifts' => $shifts,
        ]);
    }

    public function calendar(Request $request): View
    {
        $user = $request->user();

        $week = $request->filled('week')
            ? Carbon::parse($request->string('week'))->startOfWeek()
            : now()->startOfWeek();

        $weekEnd = $week->copy()->endOfWeek();
        $view = $request->string('view', 'team')->toString() === 'mine' ? 'mine' : 'team';

        $shifts = Shift::query()
            ->forTeam($user->team_id)
            ->whereBetween('starts_at', [$week, $weekEnd])
            ->with('workers')
            ->orderBy('starts_at')
            ->when($view === 'mine', fn ($q) => $q->whereHas(
                'workers',
                fn ($workerQuery) => $workerQuery->where('users.id', $user->id)
            ))
            ->get();

        $shiftsByDate = $shifts->groupBy(fn (Shift $shift) => $shift->starts_at->format('Y-m-d'));

        $days = collect(range(0, 6))->map(fn (int $offset) => $week->copy()->addDays($offset));

        $myShiftsThisWeek = $user->shifts()
            ->whereBetween('starts_at', [$week, $weekEnd])
            ->count();

        $teamShiftsThisWeek = Shift::query()
            ->forTeam($user->team_id)
            ->whereBetween('starts_at', [$week, $weekEnd])
            ->count();

        return view('shifts.calendar', [
            'user' => $user,
            'days' => $days,
            'shiftsByDate' => $shiftsByDate,
            'weekStart' => $week,
            'weekEnd' => $weekEnd,
            'prevWeek' => $week->copy()->subWeek()->toDateString(),
            'nextWeek' => $week->copy()->addWeek()->toDateString(),
            'view' => $view,
            'myShiftsThisWeek' => $myShiftsThisWeek,
            'teamShiftsThisWeek' => $teamShiftsThisWeek,
        ]);
    }

    public function mine(Request $request): View
    {
        $user = $request->user();

        $upcoming = $user->shifts()
            ->with('team')
            ->where('ends_at', '>=', now())
            ->orderBy('starts_at')
            ->get();

        $past = $user->shifts()
            ->with('team')
            ->past()
            ->orderByDesc('starts_at')
            ->get();

        return view('shifts.mine', [
            'upcoming' => $upcoming,
            'past' => $past,
        ]);
    }

    public function claim(Request $request, Shift $shift): RedirectResponse
    {
        $user = $request->user();

        if ($shift->team_id !== $user->team_id) {
            abort(403);
        }

        if ($shift->ends_at->isPast()) {
            return back()->with('error', 'This shift has already ended.');
        }

        if ($shift->isClaimedBy($user)) {
            return back()->with('error', 'You are already on this shift.');
        }

        if (! $shift->hasOpenSlot()) {
            return back()->with('error', 'This shift is full.');
        }

        $shift->workers()->attach($user->id, ['status' => 'confirmed']);

        return back()->with('status', 'Shift booked successfully.');
    }

    public function release(Request $request, Shift $shift): RedirectResponse
    {
        $user = $request->user();

        if (! $shift->isClaimedBy($user)) {
            return back()->with('error', 'You are not assigned to this shift.');
        }

        if ($shift->starts_at->isPast()) {
            return back()->with('error', 'You cannot leave a shift that has already started.');
        }

        $shift->workers()->detach($user->id);

        return back()->with('status', 'You have been removed from this shift.');
    }
}
