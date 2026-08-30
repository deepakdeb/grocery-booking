<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        User::firstOrCreate(
            ['email' => 'admin@grocery.test'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password123'),
                'role_id' => Role::where('name', 'admin')->value('id'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@grocery.test'],
            [
                'name' => 'Regular User',
                'password' => bcrypt('password123'),
                'role_id' => Role::where('name', 'user')->value('id'),
            ]
        );
    }
}
