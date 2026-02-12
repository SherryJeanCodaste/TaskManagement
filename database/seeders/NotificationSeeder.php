<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Get developer user
        $developer = User::where('email', 'developer@taskhive.com')->first();
        
        if ($developer) {
            // Create sample notifications
            Notification::create([
                'user_id' => $developer->id,
                'type' => 'new_task',
                'title' => 'New Task Assigned',
                'message' => 'Mobile App Dashboard UI has been assigned to you',
                'task_id' => null,
                'is_read' => false,
                'created_at' => now()->subHours(2),
            ]);

            Notification::create([
                'user_id' => $developer->id,
                'type' => 'approved',
                'title' => 'Task Approved',
                'message' => 'Landing Page Redesign has been approved',
                'task_id' => null,
                'is_read' => false,
                'created_at' => now()->subHours(5),
            ]);

            Notification::create([
                'user_id' => $developer->id,
                'type' => 'status_update',
                'title' => 'Task Status Updated',
                'message' => 'E-commerce Checkout Flow is now in review',
                'task_id' => null,
                'is_read' => false,
                'created_at' => now()->subDay(),
            ]);

            Notification::create([
                'user_id' => $developer->id,
                'type' => 'comment',
                'title' => 'Customer Commented',
                'message' => 'New comment on Social Media Banner Pack',
                'task_id' => null,
                'is_read' => false,
                'created_at' => now()->subDays(2),
            ]);

            Notification::create([
                'user_id' => $developer->id,
                'type' => 'new_task',
                'title' => 'Priority Task',
                'message' => 'High priority task requires immediate attention',
                'task_id' => null,
                'is_read' => false,
                'created_at' => now()->subDays(3),
            ]);
        }

        // Get customer user
        $customer = User::where('email', 'customer@taskhive.com')->first();
        
        if ($customer) {
            Notification::create([
                'user_id' => $customer->id,
                'type' => 'status_update',
                'title' => 'Task Status Updated',
                'message' => 'Your task "Mobile App Dashboard UI" is now in progress',
                'task_id' => null,
                'is_read' => false,
                'created_at' => now()->subHours(1),
            ]);

            Notification::create([
                'user_id' => $customer->id,
                'type' => 'approved',
                'title' => 'Task Completed',
                'message' => 'Landing Page Redesign has been completed',
                'task_id' => null,
                'is_read' => false,
                'created_at' => now()->subHours(3),
            ]);
        }
    }
}
