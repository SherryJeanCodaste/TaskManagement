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
        // System Admin Account
        User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin@taskhive.com',
            'password' => bcrypt('Admin@123'),
            'role' => 'admin',
        ]);

        // Test User Account
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'customer',
        ]);

        // Customer Account
        User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@taskhive.com',
            'password' => bcrypt('Customer@123'),
            'role' => 'customer',
        ]);

        // Developer Accounts
        User::factory()->create([
            'name' => 'John Smith',
            'email' => 'developer@taskhive.com',
            'password' => bcrypt('Developer@123'),
            'role' => 'developer',
            'developer_type' => 'frontend',
        ]);

        User::factory()->create([
            'name' => 'Sarah Johnson',
            'email' => 'backend@taskhive.com',
            'password' => bcrypt('Developer@123'),
            'role' => 'developer',
            'developer_type' => 'backend',
        ]);

        User::factory()->create([
            'name' => 'Mike Wilson',
            'email' => 'serveradmin@taskhive.com',
            'password' => bcrypt('Developer@123'),
            'role' => 'developer',
            'developer_type' => 'server_administrator',
        ]);

        // Employee Accounts
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@taskhive.com',
            'password' => bcrypt('Employee@123'),
            'role' => 'employee',
        ]);

        User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@taskhive.com',
            'password' => bcrypt('Employee@123'),
            'role' => 'employee',
        ]);
    }
}
