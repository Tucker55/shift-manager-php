<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shift extends Model
{
    protected $fillable = [
        'team_id',
        'title',
        'location',
        'starts_at',
        'ends_at',
        'slots',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function workers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('status')
            ->withTimestamps();
    }

    public function scopeForTeam(Builder $query, ?int $teamId): Builder
    {
        return $query->when($teamId, fn (Builder $q) => $q->where('team_id', $teamId));
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('starts_at', '>=', now());
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where('ends_at', '<', now());
    }

    public function filledCount(): int
    {
        return $this->workers()->count();
    }

    public function hasOpenSlot(): bool
    {
        return $this->filledCount() < $this->slots;
    }

    public function isClaimedBy(User $user): bool
    {
        return $this->workers()->where('user_id', $user->id)->exists();
    }

    public function durationHours(): float
    {
        return round($this->starts_at->diffInMinutes($this->ends_at) / 60, 1);
    }

    public function availabilityLabel(?User $user = null): string
    {
        if ($this->ends_at->isPast()) {
            return 'Ended';
        }

        if ($user && $this->isClaimedBy($user)) {
            return "You're on this";
        }

        if (! $this->hasOpenSlot()) {
            return 'Covered';
        }

        return 'Open';
    }

    public function availabilityColor(?User $user = null): string
    {
        return match ($this->availabilityLabel($user)) {
            'Open' => 'green',
            "You're on this" => 'indigo',
            'Covered' => 'amber',
            default => 'gray',
        };
    }
}
