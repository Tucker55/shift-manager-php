<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $team = $user->team;

        $members = $team
            ? $team->users()->orderBy('name')->get()
            : collect();

        return view('team.index', [
            'team' => $team,
            'members' => $members,
        ]);
    }
}
