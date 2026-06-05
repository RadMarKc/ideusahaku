<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MicroBusinessIdeaScore extends Model
{
    protected $fillable = [
        'micro_business_idea_id',
        'criterion_id',
        'score',
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    public function idea(): BelongsTo
    {
        return $this->belongsTo(MicroBusinessIdea::class, 'micro_business_idea_id');
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Criterion::class);
    }
}
