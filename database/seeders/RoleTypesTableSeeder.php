<?php

namespace Database\Seeders;

use App\Models\RoleType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['id' => 1, 'name' => 'admin'],
            ['id' => 2, 'name' => 'visitor'],
        ];

        foreach ($items as $item) {
            RoleType::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
