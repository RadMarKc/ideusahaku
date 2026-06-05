<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormulaSetting extends Model
{
    protected $fillable = [
        'modal_weight',
        'location_weight',
        'time_weight',
        'is_active',
    ];

    protected $casts = [
        'modal_weight' => 'decimal:2',
        'location_weight' => 'decimal:2',
        'time_weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public static function current(): self
    {
        return static::query()->orderBy('id')->firstOrCreate([], [
            'modal_weight' => 0.45,
            'location_weight' => 0.30,
            'time_weight' => 0.25,
            'is_active' => true,
        ]);
    }
}
