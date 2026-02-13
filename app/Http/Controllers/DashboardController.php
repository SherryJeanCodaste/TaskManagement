<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function adminDashboard()
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            return redirect('/dashboard');
        }

        // Get statistics
        $stats = [
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'total_tasks' => Task::count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'in_progress_tasks' => Task::where('status', 'in_progress')->count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
        ];

        // Get projects with all relationships for project cards
        $projects = Project::with([
            'customer',
            'frontendDeveloper',
            'backendDeveloper', 
            'serverAdmin',
            'tasks'
        ])->get()->map(function ($project) {
            // Calculate overall progress
            $totalTasks = $project->tasks->count();
            $completedTasks = $project->tasks->where('status', 'completed')->count();
            $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

            return [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'status' => $project->status,
                'customer' => $project->customer ? $project->customer->name : 'No Customer',
                'frontend_developer' => $project->frontendDeveloper ? $project->frontendDeveloper->name : null,
                'backend_developer' => $project->backendDeveloper ? $project->backendDeveloper->name : null,
                'server_admin' => $project->serverAdmin ? $project->serverAdmin->name : null,
                'total_tasks' => $totalTasks,
                'overall_progress' => $overallProgress,
            ];
        });

        return Inertia::render('AdminDashboard', [
            'stats' => $stats,
            'projects' => $projects,
        ]);
    }

    /**
     * Customer Dashboard
     */
    public function customerDashboard()
    {
        $user = auth()->user();

        if ($user->role !== 'customer') {
            return redirect('/dashboard');
        }

        // Get customer's project
        $project = Project::where('customer_id', $user->id)
            ->with(['frontendDeveloper', 'backendDeveloper', 'serverAdmin'])
            ->first();

        // Get task statistics
        $stats = [
            'total_tasks' => Task::where('customer_id', $user->id)->count(),
            'pending' => Task::where('customer_id', $user->id)->where('status', 'pending')->count(),
            'assigned' => Task::where('customer_id', $user->id)->where('status', 'assigned')->count(),
            'in_progress' => Task::where('customer_id', $user->id)->where('status', 'in_progress')->count(),
            'review' => Task::where('customer_id', $user->id)->where('status', 'review')->count(),
            'completed' => Task::where('customer_id', $user->id)->where('status', 'completed')->count(),
        ];

        // Get recent tasks
        $recentTasks = Task::where('customer_id', $user->id)
            ->with(['project', 'assignedDeveloper'])
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('CustomerDashboard', [
            'project' => $project,
            'stats' => $stats,
            'recentTasks' => $recentTasks,
        ]);
    }

    /**
     * Developer Dashboard
     */
    public function developerDashboard()
    {
        $user = auth()->user();

        if ($user->role !== 'developer') {
            return redirect('/dashboard');
        }

        // Get task statistics
        $stats = [
            'assigned' => Task::where('assigned_developer_id', $user->id)
                ->where('status', 'assigned')
                ->count(),
            'in_progress' => Task::where('assigned_developer_id', $user->id)
                ->where('status', 'in_progress')
                ->count(),
            'under_review' => Task::where('assigned_developer_id', $user->id)
                ->where('status', 'review')
                ->count(),
            'completed' => Task::where('assigned_developer_id', $user->id)
                ->where('status', 'completed')
                ->count(),
        ];

        // Get recent tasks
        $recentTasks = Task::where('assigned_developer_id', $user->id)
            ->with(['customer', 'project'])
            ->latest()
            ->take(4)
            ->get();

        return Inertia::render('DeveloperDashboard', [
            'stats' => $stats,
            'recentTasks' => $recentTasks,
        ]);
    }
}
