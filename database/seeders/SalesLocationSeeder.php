<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesLocation;

class SalesLocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Sempur - Kampus', 'address' => 'Area Kampus', 'description' => 'Stand di depan kampus'],
            ['name' => 'SSA - Mal', 'address' => 'Dekat Gerbang Kebun Raya (Sebrang Lippo PLaza)', 'description' => 'SSA Kebun Raya'],
        ];

        foreach ($locations as $location) {
            SalesLocation::create($location);
        }
    }
}
