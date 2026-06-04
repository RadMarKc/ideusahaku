<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MicroBusinessIdea extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'capital_min',
        'capital_max',
        'free_time_min_hours',
        'free_time_max_hours',
        'suitable_locations',
        'is_active',
    ];

    protected $casts = [
        'capital_min' => 'integer',
        'capital_max' => 'integer',
        'free_time_min_hours' => 'integer',
        'free_time_max_hours' => 'integer',
        'suitable_locations' => 'array',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
