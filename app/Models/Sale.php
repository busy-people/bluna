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

    // Auto create cashflow when sale is created
    protected static function booted()
    {
        static::created(function ($sale) {
            \App\Models\CashFlow::create([
                'date' => $sale->date,
                'type' => 'income',
                'category' => 'penjualan',
                'amount' => $sale->total,
                'description' => 'Penjualan ' . ($sale->product_type === 'small' ? 'Botol Kecil' : 'Botol Besar') .
                                 ' x' . $sale->quantity .
                                 ($sale->location ? ' - ' . $sale->location->name : ''),
            ]);
        });

        static::updated(function ($sale) {
            // Update corresponding cashflow if exists
            $cashflow = \App\Models\CashFlow::where('date', $sale->date)
                ->where('type', 'income')
                ->where('category', 'penjualan')
                ->whereRaw("description LIKE ?", ['%' . $sale->product_type . '%x' . $sale->getOriginal('quantity') . '%'])
                ->first();

            if ($cashflow) {
                $cashflow->update([
                    'amount' => $sale->total,
                    'description' => 'Penjualan ' . ($sale->product_type === 'small' ? 'Botol Kecil' : 'Botol Besar') .
                                     ' x' . $sale->quantity .
                                     ($sale->location ? ' - ' . $sale->location->name : ''),
                ]);
            }
        });

        static::deleted(function ($sale) {
            // Delete corresponding cashflow
            \App\Models\CashFlow::where('date', $sale->date)
                ->where('type', 'income')
                ->where('category', 'penjualan')
                ->whereRaw("description LIKE ?", ['%' . $sale->product_type . '%'])
                ->delete();
        });
    }
}
