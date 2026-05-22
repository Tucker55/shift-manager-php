<?php

namespace Database\Seeders;

use App\Models\Shift;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $team = Team::create([
            'name' => 'Bean & Brew Coffee',
            'location' => '42 Roast Street',
        ]);

        $weekStart = now()->startOfWeek();

        $alex = User::create([
            'name' => 'Alex',
            'email' => 'alex@beanbrew.coffee',
            'password' => Hash::make('password'),
            'team_id' => $team->id,
            'role' => 'barista',
            'email_verified_at' => now(),
        ]);

        $chris = User::create([
            'name' => 'Chris',
            'email' => 'chris@beanbrew.coffee',
            'password' => Hash::make('password'),
            'team_id' => $team->id,
            'role' => 'barista',
            'email_verified_at' => now(),
        ]);

        $taylor = User::create([
            'name' => 'Taylor',
            'email' => 'taylor@beanbrew.coffee',
            'password' => Hash::make('password'),
            'team_id' => $team->id,
            'role' => 'manager',
            'email_verified_at' => now(),
        ]);

        $opening = Shift::create([
            'team_id' => $team->id,
            'title' => 'Opening — espresso bar',
            'location' => 'Main bar',
            'starts_at' => $weekStart->copy()->addDays(1)->setTime(6, 30),
            'ends_at' => $weekStart->copy()->addDays(1)->setTime(14, 30),
            'slots' => 2,
            'notes' => 'Open tills, dial in grinders, prep pastries',
        ]);

        $rush = Shift::create([
            'team_id' => $team->id,
            'title' => 'Morning rush',
            'location' => 'Main bar + till',
            'starts_at' => $weekStart->copy()->addDays(2)->setTime(7, 0),
            'ends_at' => $weekStart->copy()->addDays(2)->setTime(12, 0),
            'slots' => 2,
            'notes' => 'Busy commute hours',
        ]);

        $closing = Shift::create([
            'team_id' => $team->id,
            'title' => 'Closing — clean down',
            'location' => 'Whole shop',
            'starts_at' => $weekStart->copy()->addDays(4)->setTime(14, 0),
            'ends_at' => $weekStart->copy()->addDays(4)->setTime(22, 0),
            'slots' => 1,
            'notes' => 'Lock up, mop, restock milk for tomorrow',
        ]);

        $brunch = Shift::create([
            'team_id' => $team->id,
            'title' => 'Saturday brunch',
            'location' => 'Main bar',
            'starts_at' => $weekStart->copy()->addDays(5)->setTime(8, 0),
            'ends_at' => $weekStart->copy()->addDays(5)->setTime(16, 0),
            'slots' => 3,
            'notes' => 'Extra pastries, expect a queue',
        ]);

        // ---------- past shifts (this month) ----------
        $pastShifts = [];

        for ($i = 1; $i <= 18; $i++) {
            $day = now()->startOfMonth()->addDays($i - 1);
            if ($day->isWeekend()) {
                continue;
            }
            if ($day->gte(now())) {
                break;
            }

            if ($i % 3 === 0) {
                $shift = Shift::create([
                    'team_id' => $team->id,
                    'title' => 'Opening — espresso bar',
                    'location' => 'Main bar',
                    'starts_at' => $day->copy()->setTime(6, 30),
                    'ends_at' => $day->copy()->setTime(14, 30),
                    'slots' => 2,
                    'notes' => 'Open tills, dial in grinders',
                ]);
                $shift->workers()->attach($alex->id, ['status' => 'confirmed']);
                $shift->workers()->attach($chris->id, ['status' => 'confirmed']);
                $pastShifts[] = $shift;
            } elseif ($i % 3 === 1) {
                $shift = Shift::create([
                    'team_id' => $team->id,
                    'title' => 'Morning rush',
                    'location' => 'Main bar + till',
                    'starts_at' => $day->copy()->setTime(7, 0),
                    'ends_at' => $day->copy()->setTime(12, 0),
                    'slots' => 2,
                    'notes' => 'Busy commute hours',
                ]);
                $shift->workers()->attach($alex->id, ['status' => 'confirmed']);
                $pastShifts[] = $shift;
            } else {
                $shift = Shift::create([
                    'team_id' => $team->id,
                    'title' => 'Closing — clean down',
                    'location' => 'Whole shop',
                    'starts_at' => $day->copy()->setTime(14, 0),
                    'ends_at' => $day->copy()->setTime(22, 0),
                    'slots' => 1,
                    'notes' => 'Lock up, restock',
                ]);
                $shift->workers()->attach($chris->id, ['status' => 'confirmed']);
                $pastShifts[] = $shift;
            }
        }

        // ---------- last month — a few shifts for history ----------
        $lastMonth = now()->subMonth()->startOfMonth();
        foreach ([3, 7, 12, 16, 20, 24] as $d) {
            $day = $lastMonth->copy()->addDays($d - 1);
            if ($day->isWeekend()) {
                continue;
            }
            $shift = Shift::create([
                'team_id' => $team->id,
                'title' => $d % 2 === 0 ? 'Morning rush' : 'Opening — espresso bar',
                'location' => 'Main bar',
                'starts_at' => $day->copy()->setTime(7, 0),
                'ends_at' => $day->copy()->setTime(13, 0),
                'slots' => 2,
            ]);
            $shift->workers()->attach($alex->id, ['status' => 'confirmed']);
        }

        // Each barista already has something on their upcoming schedule
        $opening->workers()->attach($alex->id, ['status' => 'confirmed']);
        $rush->workers()->attach($chris->id, ['status' => 'confirmed']);
        $brunch->workers()->attach($alex->id, ['status' => 'confirmed']);
        $closing->workers()->attach($alex->id, ['status' => 'confirmed']);
    }
}
