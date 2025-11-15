<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Admin (no employee_id)
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // HR (no employee_id)
        User::create([
            'name' => 'HR User',
            'email' => 'hr@example.com',
            'password' => bcrypt('password'),
            'role' => 'hr',
        ]);

    }
}
