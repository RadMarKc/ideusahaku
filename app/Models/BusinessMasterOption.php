<?php

namespace App\Models;

use App\Services\RecommendationDataService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BusinessMasterOption extends Model
{
    public const TYPE_CAPITAL = 'capital';

    public const TYPE_LOCATION = 'location';

    public const TYPE_TIME = 'time';

    protected $fillable = [
        'type',
        'code',
        'label',
        'score',
        'value_min',
        'value_max',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'score' => 'integer',
        'value_min' => 'integer',
        'value_max' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    protected static function booted(): void
    {
        static::saved(fn () => self::flushRecommendationCache());
        static::deleted(fn () => self::flushRecommendationCache());
    }

    private static function flushRecommendationCache(): void
    {
        app(RecommendationDataService::class)->flush();
    }
}
