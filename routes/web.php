<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Sudhu logout na kora user der jonno
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Sudhu login kora user der jonno
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/theme', [ThemeController::class, 'update'])->name('theme.update');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:director')->group(function () {
        Route::get('/director/dashboard', [DashboardController::class, 'director'])->name('director.dashboard');

        Route::resource('departments', DepartmentController::class)->except(['show', 'destroy']);

        Route::resource('employees', EmployeeController::class)
            ->parameters(['employees' => 'user'])
            ->except(['show', 'destroy']);
        Route::patch('/employees/{user}/status', [EmployeeController::class, 'toggleStatus'])->name('employees.status');
        Route::post('/employees/{user}/appoint-head', [EmployeeController::class, 'appointHead'])->name('employees.appoint-head');

        // Sudhu Director project toiri ar edit korte pare
        Route::resource('projects', ProjectController::class)->only(['create', 'store', 'edit', 'update']);
    });

    Route::middleware('role:head')->group(function () {
        Route::get('/head/dashboard', [DashboardController::class, 'head'])->name('head.dashboard');
    });

    Route::middleware('role:employee')->group(function () {
        Route::get('/employee/dashboard', [DashboardController::class, 'employee'])->name('employee.dashboard');
    });

    // Director ar Head dujon-i project dekhte pare
    Route::middleware('role:director,head')->group(function () {
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{project}', [ProjectController::class, 'show'])
            ->whereNumber('project')
            ->name('projects.show');
    });
});