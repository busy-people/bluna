<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'category',
        'base_points',
        'unit',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function contributions(): HasMany
    {
        return $this->hasMany(Contribution::class);
    }

    // Categories available
    public static function categories()
    {
        return [
            'belanja' => 'Belanja Bahan',
            'produksi' => 'Produksi',
            'jaga_stand' => 'Jaga Stand',
            'promosi' => 'Marketing & Promosi',
            'administrasi' => 'Administrasi',
            'pengembangan' => 'Pengembangan Bisnis',
        ];
    }
}
