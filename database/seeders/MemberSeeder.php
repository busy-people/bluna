<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'name' => 'Aldi',
                'email' => 'aldiptm@gmail.com',
                'phone' => '085712821212',
                'role' => 'member',
                'is_active' => 1,
                'notes' => '',
            ],
            [
                'name' => 'Ikhsan',
                'email' => 'ikhsanfrmnsh@gmail.com',
                'phone' => '085162819626',
                'role' => 'member',
                'is_active' => 1,
                'notes' => '',
            ],
            [
                'name' => 'Kurniawan',
                'email' => 'kurniawan@gmail.com',
                'phone' => '08621652121',
                'role' => 'member',
                'is_active' => 1,
                'notes' => '',
            ],
            [
                'name' => 'Rengga',
                'email' => 'renggaamln@gmail.com',
                'phone' => '085152671891',
                'role' => 'member',
                'is_active' => 1,
                'notes' => '',
            ],
        ];

        foreach ($members as $member) {
            Member::create($member);
        }
    }
}
