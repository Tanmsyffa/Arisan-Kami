<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Cek jika belum ada, lalu buat
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin', 'guard_name' => 'web']);
        }

        if (!Role::where('name', 'member')->exists()) {
            Role::create(['name' => 'member', 'guard_name' => 'web']);
        }
    }
}
