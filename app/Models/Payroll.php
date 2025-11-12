<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    protected $fillable = [
        'period',
        'total_revenue',
        'operational_cost',
        'net_salary',
        'total_points',
        'point_value',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_revenue' => 'decimal:2',
        'operational_cost' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'point_value' => 'decimal:2',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(PayrollDetail::class);
    }
}
