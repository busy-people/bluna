<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            // Belanja
            ['name' => 'Belanja Bahan', 'category' => 'belanja', 'base_points' => 10, 'unit' => 'per trip'],

            // Ambil Stiker
            ['name' => 'Ambil Stiker', 'category' => 'belanja', 'base_points' => 5, 'unit' => 'per trip'],

            // Beli Botol
            ['name' => 'Beli Botol', 'category' => 'belanja', 'base_points' => 10, 'unit' => 'per trip'],

            // Produksi
            ['name' => 'Produksi', 'category' => 'produksi', 'base_points' => 20, 'unit' => 'per shift'],

            // Desain
            ['name' => 'Desain', 'category' => 'desain', 'base_points' => 10, 'unit' => 'per post'],

            // Tempat Produksi
            ['name' => 'Tempat Produksi', 'category' => 'produksi', 'base_points' => 15, 'unit' => 'per shift'],

            // Jaga Stand
            ['name' => 'Jaga Stand', 'category' => 'jaga_stand', 'base_points' => 25, 'unit' => 'per shift'],

            // Konten
            ['name' => 'Buat Konten', 'category' => 'promosi', 'base_points' => 15, 'unit' => 'per post'],

            // Administrasi
            ['name' => 'Pembukuan', 'category' => 'administrasi', 'base_points' => 10, 'unit' => 'per absen'],
        ];

        foreach ($activities as $activity) {
            Activity::create($activity);
        }
    }
}
