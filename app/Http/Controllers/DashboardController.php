<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user()->load('team');

        $myUpcoming = Shift::query()
            ->forTeam($user->team_id)
            ->upcoming()
            ->whereHas('workers', fn ($q) => $q->where('users.id', $user->id))
            ->orderBy('starts_at')
            ->get();

        $openShiftsCount = Shift::query()
            ->forTeam($user->team_id)
            ->upcoming()
            ->get()
            ->filter(fn (Shift $shift) => $shift->hasOpenSlot() && ! $shift->isClaimedBy($user))
            ->count();

        $teamSize = $user->team_id
            ? $user->team()->first()?->users()->count() ?? 0
            : 0;

        return view('dashboard', [
            'user' => $user,
            'myUpcoming' => $myUpcoming,
            'openShiftsCount' => $openShiftsCount,
            'teamSize' => $teamSize,
        ]);
    }
}
