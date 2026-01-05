<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        Role::insert([
            ['name' => 'admin',  'label' => 'Admin', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'seller', 'label' => 'Seller',        'created_at' => $now, 'updated_at' => $now],
            ['name' => 'buyer',  'label' => 'Buyer',       'created_at' => $now, 'updated_at' => $now],
        ]);

    }
}
