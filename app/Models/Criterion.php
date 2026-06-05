<?php

namespace App\Models;

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
}
