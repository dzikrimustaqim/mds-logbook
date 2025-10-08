<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InternLogbookController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Landing page
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Main redirector dashboard
Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->hasRole('student')) {
            return redirect()->route('student.dashboard');
        }
        return view('dashboard'); // Fallback
    })->name('dashboard');

    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

// Student routes - ALLOW DELETE (student can delete their own logbook)
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');
    
    // Full CRUD - student can delete their own logbook
    Route::resource('logbooks', InternLogbookController::class);
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('users', UserController::class);

    Route::prefix('logbooks')->name('logbooks.')->group(function () {
        Route::get('/', [InternLogbookController::class, 'indexAdmin'])->name('index');
        Route::get('/{intern_logbook}', [InternLogbookController::class, 'show'])->name('show');
        Route::patch('/{intern_logbook}/approve', [InternLogbookController::class, 'approve'])->name('approve');
        Route::delete('/{intern_logbook}', [InternLogbookController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';