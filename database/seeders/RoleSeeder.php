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
            ['name' => '1',  'label' => 'Admin', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '2', 'label' => 'Seller', 'created_at' => $now, 'updated_at' => $now],
            ['name' => '3',  'label' => 'Buyer', 'created_at' => $now, 'updated_at' => $now],
        ]);

    }
}
