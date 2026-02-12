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
        return Inertia::render('Dashboard');
    } elseif ($user->role === 'customer') {
        return redirect('/customer/dashboard');
    } elseif ($user->role === 'employee') {
        return redirect('/employee/dashboard');
    } elseif ($user->role === 'developer') {
        return redirect('/developer/dashboard');
    }
    
    return Inertia::render('Dashboard');
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
    Route::get('users', function () {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }
        return Inertia::render('Users');
    })->name('users');

    Route::get('tasks', function () {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }
        return Inertia::render('Tasks');
    })->name('tasks');

    Route::get('tasks/{id}', function ($id) {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }
        return Inertia::render('AdminTaskDetail', [
            'taskId' => $id
        ]);
    })->name('tasks.show');

    Route::get('reports', function () {
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard');
        }
        return Inertia::render('Reports');
    })->name('reports');
});

// Customer Routes
Route::middleware(['auth', 'verified'])->prefix('customer')->group(function () {
    Route::get('dashboard', function () {
        if (auth()->user()->role !== 'customer') {
            return redirect('/dashboard');
        }
        return Inertia::render('CustomerDashboard');
    })->name('customer.dashboard');

    Route::get('tasks', function () {
        if (auth()->user()->role !== 'customer') {
            return redirect('/dashboard');
        }
        return Inertia::render('CustomerTasks');
    })->name('customer.tasks');

    Route::get('tasks/{id}', function ($id) {
        if (auth()->user()->role !== 'customer') {
            return redirect('/dashboard');
        }
        return Inertia::render('CustomerTaskDetail', [
            'taskId' => $id
        ]);
    })->name('customer.tasks.show');
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
    Route::get('dashboard', function () {
        if (auth()->user()->role !== 'developer') {
            return redirect('/dashboard');
        }
        return Inertia::render('DeveloperDashboard');
    })->name('developer.dashboard');

    Route::get('tasks', function () {
        if (auth()->user()->role !== 'developer') {
            return redirect('/dashboard');
        }
        return Inertia::render('DeveloperTasks');
    })->name('developer.tasks');

    Route::get('tasks/{id}', function ($id) {
        if (auth()->user()->role !== 'developer') {
            return redirect('/dashboard');
        }
        return Inertia::render('DeveloperTaskDetail', [
            'taskId' => $id
        ]);
    })->name('developer.tasks.show');

    Route::get('completed', function () {
        if (auth()->user()->role !== 'developer') {
            return redirect('/dashboard');
        }
        return Inertia::render('DeveloperCompleted');
    })->name('developer.completed');
});

require __DIR__.'/settings.php';
