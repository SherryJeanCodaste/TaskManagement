<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }

        $query = Project::with(['customer', 'frontendDeveloper', 'backendDeveloper', 'serverAdmin'])
            ->withCount('tasks');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        $projects = $query->latest()->get();

        return Inertia::render('Projects', [
            'projects' => $projects,
            'filters' => [
                'status' => $request->status ?? 'all',
                'search' => $request->search ?? '',
            ],
        ]);
    }

    public function create()
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }

        $customers = User::where('role', 'customer')->get(['id', 'name']);
        $frontendDevs = User::where('role', 'developer')
            ->where('developer_type', 'frontend')
            ->get(['id', 'name', 'developer_type']);
        $backendDevs = User::where('role', 'developer')
            ->where('developer_type', 'backend')
            ->get(['id', 'name', 'developer_type']);
        $serverAdmins = User::where('role', 'developer')
            ->where('developer_type', 'server_administrator')
            ->get(['id', 'name', 'developer_type']);

        return response()->json([
            'customers' => $customers,
            'frontendDevelopers' => $frontendDevs,
            'backendDevelopers' => $backendDevs,
            'serverAdministrators' => $serverAdmins,
        ]);
    }

    public function store(Request $request)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string',
            'customer_id' => 'required|exists:users,id',
            'frontend_developer_id' => 'nullable|exists:users,id',
            'backend_developer_id' => 'nullable|exists:users,id',
            'server_admin_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:active,archived',
        ]);

        $validated['status'] = $validated['status'] ?? 'active';

        $project = Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }

        $project->load([
            'customer',
            'frontendDeveloper',
            'backendDeveloper',
            'serverAdmin',
            'tasks' => function ($query) {
                $query->with(['assignedDeveloper'])->latest();
            }
        ]);

        $taskStats = [
            'total' => $project->tasks->count(),
            'pending' => $project->tasks->where('status', 'pending')->count(),
            'assigned' => $project->tasks->where('status', 'assigned')->count(),
            'in_progress' => $project->tasks->where('status', 'in_progress')->count(),
            'review' => $project->tasks->where('status', 'review')->count(),
            'completed' => $project->tasks->where('status', 'completed')->count(),
        ];

        return Inertia::render('ProjectDetail', [
            'project' => $project,
            'taskStats' => $taskStats,
        ]);
    }

    public function edit(Project $project)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }

        $customers = User::where('role', 'customer')->get(['id', 'name']);
        $frontendDevs = User::where('role', 'developer')
            ->where('developer_type', 'frontend')
            ->get(['id', 'name', 'developer_type']);
        $backendDevs = User::where('role', 'developer')
            ->where('developer_type', 'backend')
            ->get(['id', 'name', 'developer_type']);
        $serverAdmins = User::where('role', 'developer')
            ->where('developer_type', 'server_administrator')
            ->get(['id', 'name', 'developer_type']);

        return response()->json([
            'project' => $project,
            'customers' => $customers,
            'frontendDevelopers' => $frontendDevs,
            'backendDevelopers' => $backendDevs,
            'serverAdministrators' => $serverAdmins,
        ]);
    }

    public function update(Request $request, Project $project)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,' . $project->id,
            'description' => 'nullable|string',
            'customer_id' => 'required|exists:users,id',
            'frontend_developer_id' => 'nullable|exists:users,id',
            'backend_developer_id' => 'nullable|exists:users,id',
            'server_admin_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:active,archived',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }

        // Check if project has tasks
        if ($project->tasks()->count() > 0) {
            return back()->with('error', 'Cannot delete project with existing tasks. Please archive it instead.');
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function archive(Project $project)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }

        $project->update(['status' => 'archived']);

        return back()->with('success', 'Project archived successfully.');
    }

    public function activate(Project $project)
    {
        // Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }

        $project->update(['status' => 'active']);

        return back()->with('success', 'Project activated successfully.');
    }
}
