<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class MicroBusinessIdea extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'capital_min',
        'capital_max',
        'capital_estimate',
        'free_time_min_hours',
        'free_time_max_hours',
        'suitable_locations',
        'location_label',
        'time_label',
        'is_active',
    ];

    protected $appends = [
        'capital_score',
        'location_score',
        'time_score',
        'total_score',
    ];

    protected $casts = [
        'capital_min' => 'integer',
        'capital_max' => 'integer',
        'capital_estimate' => 'integer',
        'free_time_min_hours' => 'integer',
        'free_time_max_hours' => 'integer',
        'suitable_locations' => 'array',
        'is_active' => 'boolean',
    ];

    public function scores(): HasMany
    {
        return $this->hasMany(MicroBusinessIdeaScore::class);
    }

    public function getCapitalScoreAttribute(): int
    {
        return $this->scoreFor('modal');
    }

    public function getLocationScoreAttribute(): int
    {
        return $this->scoreFor('lokasi');
    }

    public function getTimeScoreAttribute(): int
    {
        return $this->scoreFor('waktu');
    }

    public function getTotalScoreAttribute(): int
    {
        return $this->capital_score + $this->location_score + $this->time_score;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    private function scoreFor(string $criterionCode): int
    {
        $scores = $this->relationLoaded('scores')
            ? $this->scores
            : $this->scores()->with('criterion')->get();

        $score = $scores->first(
            fn (MicroBusinessIdeaScore $score) => $score->criterion?->code === $criterionCode
        );

        return (int) ($score?->score ?? 0);
    }
}
