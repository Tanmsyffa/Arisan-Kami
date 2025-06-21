<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'member']);

        // Assign admin role ke user pertama
        $adminUser = User::first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }
    }
}