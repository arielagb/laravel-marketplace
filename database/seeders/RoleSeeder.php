<?php

namespace Database\Seeders;
use App\Models\Role;
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
            ['label' => 'Admin', 'created_at' => $now, 'updated_at' => $now],
            ['label' => 'Seller', 'created_at' => $now, 'updated_at' => $now],
            ['label' => 'Buyer', 'created_at' => $now, 'updated_at' => $now],
        ]);

    }
}
