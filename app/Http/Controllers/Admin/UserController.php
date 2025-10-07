<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Menampilkan daftar SEMUA user (di sini kita fokus pada Student).
     * Route: admin/users
     */
    public function index()
    {
        $query = User::role('student');

        // Search functionality
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Order by latest and paginate
        $students = $query->latest()->paginate(15)->appends(request()->query());

        return view('admin.users.index', compact('students'));
    }

    /**
     * Menampilkan form untuk membuat user (Student) baru.
     * Route: admin/users/create
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan user (Student) baru.
     * Route: POST admin/users
     */
    public function store(Request $request)
    {
        // Aturan validasi
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            // Password harus diisi karena ini akun baru
            'password' => ['required', 'string', 'min:8', 'confirmed'], 
        ]);

        // 1. Buat User
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 2. Tetapkan Role 'student'
        $studentRole = Role::where('name', 'student')->first();
        if ($studentRole) {
            $user->assignRole($studentRole);
        }

        return redirect()->route('admin.users.index')->with('status', 'Akun Student ' . $user->name . ' berhasil dibuat!');
    }

    /**
     * Menampilkan detail user.
     * Route: admin/users/{user}
     */
    public function show(User $user)
    {
        // Admin bisa melihat detail Student, termasuk logbook mereka
        $logbooks = $user->logbooks()->latest()->paginate(10);
        return view('admin.users.show', compact('user', 'logbooks'));
    }

    /**
     * Menampilkan form edit user.
     * Route: admin/users/{user}/edit
     */
    public function edit(User $user)
    {
        // Pastikan Admin hanya mengedit Student (opsional, tapi disarankan)
        if ($user->hasRole('admin')) {
             return back()->with('warning', 'Tidak dapat mengedit akun Administrator di sini.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user.
     * Route: PATCH/PUT admin/users/{user}
     */
    public function update(Request $request, User $user)
    {
        // Aturan validasi (username dan email harus unik, kecuali milik user ini)
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // Password boleh kosong (nullable)
        ]);

        // Update data dasar
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];

        // Update password jika ada input baru
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'Data Student berhasil diperbarui!');
    }

    /**
     * Menghapus user.
     * Route: DELETE admin/users/{user}
     */
    public function destroy(User $user)
    {
        // Otorisasi: Mencegah Admin menghapus dirinya sendiri
        if ($user->hasRole('admin') || $user->id === auth()->id()) {
             return back()->with('warning', 'Tidak dapat menghapus akun Administrator atau diri sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'Akun Student berhasil dihapus.');
    }
}