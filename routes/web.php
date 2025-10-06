<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InternLogbookController; // Controller untuk Student
use App\Http\Controllers\Admin\UserController; // Controller untuk Admin
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Halaman utama / landing page (tanpa perlu login)
Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Logged-in & Middleware Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    // Dashboard bawaan Breeze (akan jadi dashboard umum / student dashboard)
    Route::get('/dashboard', function () {
        // Logika sederhana untuk mengarahkan user ke dashboard yang sesuai
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard'); // Dashboard Student
    })->name('dashboard');

    // Route Profile Bawaan Breeze (Bisa diakses Admin & Student)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Student Routes (Logbook CRUD) - Akses hanya untuk Role: Student
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])->group(function () {
    // Resource Route untuk Logbook (Create, Edit, Delete Logbook SENDIRI)
    Route::resource('logbooks', InternLogbookController::class)->except(['show']); 
    
    // Catatan: Route show (melihat logbook detail) dikhususkan untuk Admin saja.
});


/*
|--------------------------------------------------------------------------
| Admin Routes - Akses hanya untuk Role: Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // Nanti kita buat file view 'admin/dashboard.blade.php'
    })->name('dashboard');

    // 1. Manajemen Akun Student (Admin yang membuat akun student, sesuai permintaan)
    Route::resource('users', UserController::class);

    // 2. Melihat & Mengelola SEMUA Logbook (termasuk melihat detail)
    Route::get('logbooks', [InternLogbookController::class, 'indexAdmin'])->name('logbooks.index');
    Route::get('logbooks/{intern_logbook}', [InternLogbookController::class, 'show'])->name('logbooks.show');
    
    // Route Khusus untuk Aksi Admin
    Route::patch('logbooks/{intern_logbook}/approve', [InternLogbookController::class, 'approve'])->name('logbooks.approve');
});


// Route Otentikasi Bawaan Breeze
require __DIR__.'/auth.php';