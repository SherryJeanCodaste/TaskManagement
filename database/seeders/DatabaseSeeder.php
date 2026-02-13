<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // System Admin Account - Only essential account
        User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin@taskhive.com',
            'password' => bcrypt('Admin@123'),
            'role' => 'admin',
        ]);
    }
}
