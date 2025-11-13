<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    protected $fillable = [
        'date',
        'type',
        'category',
        'amount',
        'description',
        'receipt_photo',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public static function categories()
    {
        return [
            'expense' => [
                'beli_bahan' => 'Beli Bahan Baku',
                'alat' => 'Alat & Perlengkapan',
                'operasional' => 'Operasional',
                'transport' => 'Transport',
                'lainnya' => 'Lainnya',
            ],
            'income' => [
                'penjualan' => 'Penjualan',
                'lainnya' => 'Lainnya',
            ],
        ];
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }
}
