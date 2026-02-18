<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 1 customer
        $customer = \App\Models\User::create([
            'name' => 'John Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // Create developers
        $frontendDevs = [];
        $backendDevs = [];
        $serverAdmins = [];

        for ($i = 1; $i <= 5; $i++) {
            $frontendDevs[] = \App\Models\User::create([
                'name' => "Frontend Developer $i",
                'email' => "frontend$i@example.com",
                'password' => bcrypt('password'),
                'role' => 'frontend_developer',
            ]);

            $backendDevs[] = \App\Models\User::create([
                'name' => "Backend Developer $i",
                'email' => "backend$i@example.com",
                'password' => bcrypt('password'),
                'role' => 'backend_developer',
            ]);

            $serverAdmins[] = \App\Models\User::create([
                'name' => "Server Admin $i",
                'email' => "server$i@example.com",
                'password' => bcrypt('password'),
                'role' => 'server_administrator',
            ]);
        }

        // Create 5 projects
        for ($i = 1; $i <= 5; $i++) {
            $project = \App\Models\Project::create([
                'name' => "Project $i",
                'description' => "Description for Project $i",
            ]);

            // Randomly assign one developer of each type to the project
            $randomFrontend = $frontendDevs[array_rand($frontendDevs)];
            $randomBackend = $backendDevs[array_rand($backendDevs)];
            $randomServer = $serverAdmins[array_rand($serverAdmins)];

            \App\Models\ProjectMember::create([
                'project_id' => $project->id,
                'user_id' => $randomFrontend->id,
                'role' => 'frontend_developer',
            ]);

            \App\Models\ProjectMember::create([
                'project_id' => $project->id,
                'user_id' => $randomBackend->id,
                'role' => 'backend_developer',
            ]);

            \App\Models\ProjectMember::create([
                'project_id' => $project->id,
                'user_id' => $randomServer->id,
                'role' => 'server_administrator',
            ]);
        }
    }
}
