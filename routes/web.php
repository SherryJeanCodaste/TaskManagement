<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    $user = auth()->user();
    
    // Redirect based on role
    if ($user->role === 'admin') {
        return app(\App\Http\Controllers\DashboardController::class)->adminDashboard();
    } elseif ($user->role === 'customer') {
        return redirect('/customer/dashboard');
    } elseif ($user->role === 'developer') {
        return redirect('/developer/dashboard');
    }
    
    // Default fallback
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    // Projects Management
    Route::get('projects', [\App\Http\Controllers\ProjectController::class, 'index'])->name('projects.index');
    
    // Frontend-only create route (bypasses controller to avoid opcache issues)
    Route::get('projects/create', function () {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }
        return Inertia::render('ProjectCreate');
    })->name('projects.create');
    
    // Backend routes (will work after opcache is disabled)
    Route::post('projects', [\App\Http\Controllers\ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/{project}', [\App\Http\Controllers\ProjectController::class, 'show'])->name('projects.show');
    Route::get('projects/{project}/edit', [\App\Http\Controllers\ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('projects/{project}', [\App\Http\Controllers\ProjectController::class, 'update'])->name('projects.update');
    Route::delete('projects/{project}', [\App\Http\Controllers\ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('projects/{project}/archive', [\App\Http\Controllers\ProjectController::class, 'archive'])->name('projects.archive');
    Route::post('projects/{project}/activate', [\App\Http\Controllers\ProjectController::class, 'activate'])->name('projects.activate');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Tasks Routes
    Route::get('tasks', [\App\Http\Controllers\TaskController::class, 'index'])->name('tasks');
    Route::get('tasks/{task}', [\App\Http\Controllers\TaskController::class, 'show'])->name('tasks.show');

    // Admin Users Routes
    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::get('users/employee/create', function () {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }
        return Inertia::render('UserCreate', ['userType' => 'employee']);
    })->name('users.employee.create');
    Route::get('users/customer/create', function () {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }
        // Get all projects for selection
        // Note: Customer can only be assigned to ONE project
        // Projects without customers will be prioritized in the UI
        $projects = \App\Models\Project::get(['id', 'name', 'customer_id']);
        
        return Inertia::render('UserCreate', [
            'userType' => 'customer',
            'projects' => $projects
        ]);
    })->name('users.customer.create');
    Route::post('users/employee', [\App\Http\Controllers\UserController::class, 'storeEmployee'])->name('users.employee.store');
    Route::post('users/customer', [\App\Http\Controllers\UserController::class, 'storeCustomer'])->name('users.customer.store');
    Route::put('users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::post('users/{user}/deactivate', [\App\Http\Controllers\UserController::class, 'deactivate'])->name('users.deactivate');
    Route::delete('users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

    // Notifications routes
    Route::get('notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

    Route::get('reports', function () {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }
        return Inertia::render('Reports');
    })->name('reports');
});

// Customer Routes
Route::middleware(['auth', 'verified'])->prefix('customer')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'customerDashboard'])->name('customer.dashboard');
    Route::get('tasks', [\App\Http\Controllers\TaskController::class, 'customerTasks'])->name('customer.tasks');
    Route::get('tasks/create', [\App\Http\Controllers\TaskController::class, 'create'])->name('customer.tasks.create');
    Route::post('tasks', [\App\Http\Controllers\TaskController::class, 'store'])->name('customer.tasks.store');
    Route::get('tasks/{task}', [\App\Http\Controllers\TaskController::class, 'customerTaskDetail'])->name('customer.tasks.show');
});

// Employee Routes (placeholder for future)
Route::middleware(['auth', 'verified'])->prefix('employee')->group(function () {
    Route::get('dashboard', function () {
        if (auth()->user()->role !== 'employee') {
            return redirect('/dashboard');
        }
        return Inertia::render('Dashboard'); // Will create EmployeeDashboard later
    })->name('employee.dashboard');
});

// Developer Routes
Route::middleware(['auth', 'verified'])->prefix('developer')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'developerDashboard'])->name('developer.dashboard');
    Route::get('tasks', [\App\Http\Controllers\TaskController::class, 'developerTasks'])->name('developer.tasks');
    Route::get('tasks/{task}', [\App\Http\Controllers\TaskController::class, 'developerTaskDetail'])->name('developer.tasks.show');
    Route::post('tasks/{task}/update', [\App\Http\Controllers\TaskController::class, 'updateDeveloperTask'])->name('developer.tasks.update');
    Route::post('tasks/{task}/status', [\App\Http\Controllers\TaskController::class, 'updateStatus'])->name('developer.tasks.updateStatus');

    Route::get('completed', function () {
        if (auth()->user()->role !== 'developer') {
            return redirect('/dashboard');
        }
        return Inertia::render('DeveloperCompleted');
    })->name('developer.completed');
});

require __DIR__.'/settings.php';
