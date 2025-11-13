<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesLocation;

class SalesLocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Sempur', 'address' => 'Depan Mini Soccer (Kopi Nako)', 'description' => 'Stand di depan Mini Socccer'],
            ['name' => 'SSA', 'address' => 'Dekat Gerbang Kebun Raya (Sebrang Lippo PLaza)', 'description' => 'SSA Kebun Raya'],
        ];

        foreach ($locations as $location) {
            SalesLocation::create($location);
        }
    }
}
