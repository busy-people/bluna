<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contribution extends Model
{
    protected $fillable = [
        'member_id',
        'activity_id',
        'date',
        'quantity',
        'bonus_points',
        'total_points',
        'notes',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    // Auto calculate total points
    protected static function booted()
    {
        static::creating(function ($contribution) {
            $activity = Activity::find($contribution->activity_id);
            $contribution->total_points = ($activity->base_points * $contribution->quantity) + $contribution->bonus_points;
        });

        static::updating(function ($contribution) {
            if ($contribution->isDirty(['quantity', 'bonus_points'])) {
                $activity = Activity::find($contribution->activity_id);
                $contribution->total_points = ($activity->base_points * $contribution->quantity) + $contribution->bonus_points;
            }
        });
    }
}
