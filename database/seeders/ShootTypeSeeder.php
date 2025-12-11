<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShootType;

class ShootTypeSeeder extends Seeder
{
    public function run(): void
    {
        $shootTypes = [
            ['name' => 'Classic', 'status' => true],
            ['name' => 'Lifestyle', 'status' => true],
            ['name' => 'Luxury', 'status' => true],
            ['name' => 'Outdoor', 'status' => true],
        ];

        foreach ($shootTypes as $type) {
            ShootType::firstOrCreate(
                ['name' => $type['name']],
                ['status' => $type['status']]
            );
        }
    }
}
