<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks (Admin view)
     */
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }

        $query = Task::with(['customer', 'project', 'assignedDeveloper']);

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $tasks = $query->latest()->get();

        return Inertia::render('Tasks', [
            'tasks' => $tasks,
            'filters' => [
                'status' => $request->status ?? 'all',
                'category' => $request->category ?? 'all',
                'priority' => $request->priority ?? 'all',
                'search' => $request->search ?? '',
            ],
        ]);
    }

    /**
     * Show task detail (Admin view)
     */
    public function show(Task $task)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }

        $task->load(['customer', 'project', 'assignedDeveloper']);

        return Inertia::render('AdminTaskDetail', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for creating a new task (Customer)
     */
    public function create()
    {
        $user = auth()->user();

        if ($user->role !== 'customer') {
            return redirect('/dashboard');
        }

        // Get customer's projects
        $projects = Project::where('customer_id', $user->id)->get(['id', 'name']);

        return Inertia::render('CustomerTaskCreate', [
            'projects' => $projects,
        ]);
    }

    /**
     * Store a newly created task (Customer creates task)
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'customer') {
            return back()->with('error', 'Only customers can create tasks.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:frontend,backend,server',
            'priority' => 'required|in:low,medium,high',
            'project_id' => 'required|exists:projects,id',
        ]);

        // Get the project to find the appropriate developer
        $project = Project::findOrFail($validated['project_id']);

        // Auto-assign developer based on category
        $developerId = null;
        switch ($validated['category']) {
            case 'frontend':
                $developerId = $project->frontend_developer_id;
                break;
            case 'backend':
                $developerId = $project->backend_developer_id;
                break;
            case 'server':
                $developerId = $project->server_admin_id;
                break;
        }

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'project_id' => $validated['project_id'],
            'customer_id' => $user->id,
            'assigned_developer_id' => $developerId,
            'status' => 'assigned', // Start as assigned
        ]);

        return redirect()->route('customer.tasks')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Update task status (Developer updates)
     */
    public function updateStatus(Request $request, Task $task)
    {
        $user = auth()->user();

        if ($user->role !== 'developer') {
            return back()->with('error', 'Only developers can update task status.');
        }

        // Verify the developer is assigned to this task
        if ($task->assigned_developer_id !== $user->id) {
            return back()->with('error', 'You are not assigned to this task.');
        }

        $validated = $request->validate([
            'status' => 'required|in:assigned,in-progress,review,completed',
            'completion_proof' => 'nullable|string',
            'comment' => 'nullable|string',
        ]);

        $task->update([
            'status' => $validated['status'],
            'completion_proof' => $validated['completion_proof'] ?? $task->completion_proof,
        ]);

        return back()->with('success', 'Task status updated successfully.');
    }

    /**
     * Get developer's tasks
     */
    public function developerTasks(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'developer') {
            return redirect('/dashboard');
        }

        $query = Task::where('assigned_developer_id', $user->id)
            ->with(['customer', 'project']);

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->latest()->get();

        return Inertia::render('DeveloperTasks', [
            'tasks' => $tasks,
            'filters' => [
                'status' => $request->status ?? 'all',
                'category' => $request->category ?? 'all',
                'priority' => $request->priority ?? 'all',
            ],
        ]);
    }

    /**
     * Show developer task detail
     */
    public function developerTaskDetail(Task $task)
    {
        $user = auth()->user();

        if ($user->role !== 'developer') {
            return redirect('/dashboard');
        }

        // Verify the developer is assigned to this task
        if ($task->assigned_developer_id !== $user->id) {
            return redirect('/developer/tasks')->with('error', 'You are not assigned to this task.');
        }

        $task->load(['customer', 'project', 'comments']);

        return Inertia::render('DeveloperTaskDetail', [
            'task' => $task,
        ]);
    }

    /**
     * Update developer task progress
     */
    public function updateDeveloperTask(Request $request, Task $task)
    {
        $user = auth()->user();

        if ($user->role !== 'developer') {
            return back()->with('error', 'Only developers can update tasks.');
        }

        // Verify the developer is assigned to this task
        if ($task->assigned_developer_id !== $user->id) {
            return back()->with('error', 'You are not assigned to this task.');
        }

        // Debug: Log all request data
        \Log::info('Update request received', [
            'all_data' => $request->all(),
            'task_id' => $task->id,
        ]);

        $validated = $request->validate([
            'status' => 'required|in:assigned,in-progress,review,completed',
            'progress' => 'required|integer|min:0|max:100',
            'comment' => 'nullable|string',
            'progress_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        \Log::info('Developer task update', [
            'task_id' => $task->id,
            'status' => $validated['status'],
            'progress' => $validated['progress'],
            'comment' => $validated['comment'] ?? null,
            'has_image' => isset($validated['progress_image']),
        ]);

        $updateData = [
            'status' => $validated['status'],
            'progress' => $validated['progress'],
        ];

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('progress_image')) {
            $image = $request->file('progress_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/progress'), $imageName);
            $imagePath = 'uploads/progress/' . $imageName;
            $updateData['progress_image'] = $imagePath;
        }

        $task->update($updateData);

        // Create comment entry for this update
        if ($validated['comment'] || $imagePath || $validated['status'] !== $task->getOriginal('status')) {
            \App\Models\TaskComment::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'comment' => $validated['comment'],
                'image' => $imagePath,
                'status_update' => $validated['status'] !== $task->getOriginal('status') ? $validated['status'] : null,
                'progress_update' => $validated['progress'] !== $task->getOriginal('progress') ? $validated['progress'] : null,
            ]);
        }

        // TODO: Store comment in comments table when implemented
        // For now, we'll just update the task

        return back()->with('success', 'Task updated successfully.');
    }

    /**
     * Get customer's tasks
     */
    public function customerTasks(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'customer') {
            return redirect('/dashboard');
        }

        $query = Task::where('customer_id', $user->id)
            ->with(['project', 'assignedDeveloper']);

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $tasks = $query->latest()->get();

        return Inertia::render('CustomerTasks', [
            'tasks' => $tasks,
            'filters' => [
                'status' => $request->status ?? 'all',
            ],
        ]);
    }

    /**
     * Show customer task detail
     */
    public function customerTaskDetail(Task $task)
    {
        $user = auth()->user();

        if ($user->role !== 'customer') {
            return redirect('/dashboard');
        }

        // Verify the customer owns this task
        if ($task->customer_id !== $user->id) {
            return redirect('/customer/tasks')->with('error', 'You do not have access to this task.');
        }

        $task->load(['project', 'assignedDeveloper', 'comments']);

        return Inertia::render('CustomerTaskDetail', [
            'task' => $task,
        ]);
    }
}
