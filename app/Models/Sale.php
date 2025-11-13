<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = [
        'date',
        'location_id',
        'product_type',
        'quantity',
        'price',
        'total',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(SalesLocation::class, 'location_id');
    }
}
