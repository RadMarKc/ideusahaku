<?php

namespace App\Models;

use App\Services\RecommendationDataService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criterion extends Model
{
    protected $fillable = [
        'code',
        'name',
        'weight',
        'type',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function ideaScores(): HasMany
    {
        return $this->hasMany(MicroBusinessIdeaScore::class);
    }

    protected static function booted(): void
    {
        static::saved(fn () => app(RecommendationDataService::class)->flush());
        static::deleted(fn () => app(RecommendationDataService::class)->flush());
    }
}
