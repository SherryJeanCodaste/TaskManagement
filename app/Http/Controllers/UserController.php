<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display users management page
     */
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }

        // Get employees (developers)
        $employees = User::where('role', 'developer')
            ->withCount([
                'frontendProjects',
                'backendProjects',
                'serverAdminProjects'
            ])
            ->latest() // Sort by newest first
            ->get()
            ->map(function ($user) {
                // Get all projects for this developer
                $projects = collect();
                if ($user->developer_type === 'frontend') {
                    $projects = $user->frontendProjects;
                } elseif ($user->developer_type === 'backend') {
                    $projects = $user->backendProjects;
                } elseif ($user->developer_type === 'server_administrator') {
                    $projects = $user->serverAdminProjects;
                }

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $this->formatDeveloperType($user->developer_type),
                    'developer_type' => $user->developer_type,
                    'projects' => $projects->pluck('name')->toArray(),
                    'projects_count' => $projects->count(),
                    'status' => 'active', // You can add a status column to users table if needed
                ];
            });

        // Get customers
        $customers = User::where('role', 'customer')
            ->withCount(['createdTasks', 'customerProjects'])
            ->with('customerProjects:id,name,customer_id')
            ->latest() // Sort by newest first
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'projects' => $user->customerProjects->pluck('name')->toArray(),
                    'projects_count' => $user->customer_projects_count,
                    'tasks_created' => $user->created_tasks_count,
                    'status' => 'active',
                ];
            });

        // Get statistics
        $stats = [
            'total_employees' => $employees->count(),
            'active_employees' => $employees->where('status', 'active')->count(),
            'total_customers' => $customers->count(),
            'active_customers' => $customers->where('status', 'active')->count(),
            'total_tasks_created' => $customers->sum('tasks_created'),
        ];

        return Inertia::render('Users', [
            'employees' => $employees,
            'customers' => $customers,
            'stats' => $stats,
        ]);
    }

    /**
     * Store a new employee
     */
    public function storeEmployee(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'developer_type' => 'required|in:frontend,backend,server_administrator',
            'password' => ['required', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'developer',
            'developer_type' => $validated['developer_type'],
            'email_verified_at' => now(),
        ]);

        // Log for debugging
        \Log::info('Employee created', [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'developer_type' => $user->developer_type,
        ]);

        return redirect()->route('users')->with('success', 'Employee added successfully. Email: ' . $user->email . ' | Default Password: Password@123');
    }

    /**
     * Store a new customer
     */
    public function storeCustomer(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)],
        ];

        // If creating a new project
        if ($request->create_new_project) {
            $rules['new_project_name'] = 'required|string|max:255|unique:projects,name';
            $rules['new_project_description'] = 'nullable|string';
        } else {
            $rules['project_id'] = 'required|exists:projects,id';
        }

        $validated = $request->validate($rules);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Create new project or assign to existing
        if ($request->create_new_project) {
            $project = Project::create([
                'name' => $validated['new_project_name'],
                'description' => $validated['new_project_description'] ?? '',
                'customer_id' => $user->id,
                'status' => 'active',
            ]);

            \Log::info('Customer and Project created', [
                'customer_id' => $user->id,
                'customer_email' => $user->email,
                'project_id' => $project->id,
                'project_name' => $project->name,
            ]);

            return redirect()->route('users')->with('success', 'Customer and project created successfully. Email: ' . $user->email);
        } else {
            // Assign customer to existing project
            Project::where('id', $validated['project_id'])->update([
                'customer_id' => $user->id
            ]);

            \Log::info('Customer created', [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'project_id' => $validated['project_id'],
            ]);

            return redirect()->route('users')->with('success', 'Customer added successfully. Email: ' . $user->email);
        }
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        if ($user->role === 'developer') {
            $rules['developer_type'] = 'required|in:frontend,backend,server_administrator';
        }

        if ($request->filled('password')) {
            $rules['password'] = ['required', Password::min(8)];
        }

        $validated = $request->validate($rules);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($user->role === 'developer') {
            $updateData['developer_type'] = $validated['developer_type'];
        }

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return back()->with('success', 'User updated successfully.');
    }

    /**
     * Deactivate user
     */
    public function deactivate(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized');
        }

        // Prevent deactivating admin users
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot deactivate admin users.');
        }

        // For now, we'll just delete the user since we don't have a status field
        // In the future, you can add a 'status' field to users table and set it to 'inactive'
        $user->delete();

        return back()->with('success', 'User deactivated successfully.');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized');
        }

        // Prevent deleting admin users
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete admin users.');
        }

        // Check if user has projects or tasks
        if ($user->role === 'customer' && $user->customerProjects()->count() > 0) {
            return back()->with('error', 'Cannot delete customer with assigned projects.');
        }

        if ($user->role === 'developer') {
            $projectsCount = $user->frontendProjects()->count() + 
                           $user->backendProjects()->count() + 
                           $user->serverAdminProjects()->count();
            
            if ($projectsCount > 0) {
                return back()->with('error', 'Cannot delete developer with assigned projects.');
            }
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Format developer type for display
     */
    private function formatDeveloperType(?string $type): string
    {
        if (!$type) return 'Developer';

        return match($type) {
            'frontend' => 'Frontend Developer',
            'backend' => 'Backend Developer',
            'server_administrator' => 'Server Administrator',
            default => 'Developer',
        };
    }
}
