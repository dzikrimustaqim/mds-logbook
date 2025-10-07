<?php

namespace App\Http\Controllers;

use App\Models\InternLogbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InternLogbookController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT METHODS (CRUD Logbook Milik Sendiri)
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan daftar logbook milik student yang sedang login.
     */
    public function index()
    {
        $logbooks = Auth::user()->logbooks()->latest()->paginate(10);
        return view('logbooks.index', compact('logbooks'));
    }

    /**
     * Menampilkan form untuk membuat logbook baru.
     */
    public function create()
    {
        return view('logbooks.create');
    }

    /**
     * Menyimpan logbook baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'title' => ['nullable', 'string', 'max:255'],
            'activity' => ['required', 'string'],
        ]);

        Auth::user()->logbooks()->create($validated);

        return redirect()->route('student.logbooks.index')->with('status', 'Logbook berhasil disimpan!');
    }

    /**
     * Menampilkan form untuk mengedit logbook.
     */
    public function edit(InternLogbook $logbook)
    {
        // Otorisasi: Pastikan student hanya bisa edit logbook miliknya.
        if (Auth::id() !== $logbook->user_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit logbook ini.');
        }

        return view('logbooks.edit', compact('logbook'));
    }

    /**
     * Mengupdate logbook.
     */
    public function update(Request $request, InternLogbook $logbook)
    {
        // Otorisasi: Pastikan student hanya bisa update logbook miliknya.
        if (Auth::id() !== $logbook->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'title' => ['nullable', 'string', 'max:255'],
            'activity' => ['required', 'string'],
        ]);

        $logbook->update($validated);

        return redirect()->route('student.logbooks.index')->with('status', 'Logbook berhasil diperbarui!');
    }

    /**
     * Menghapus logbook.
     * PERBAIKAN #1: Nama variabel diubah menjadi $intern_logbook
     * PERBAIKAN #2: Logika otorisasi diperbaiki agar Admin bisa menghapus.
     */
    public function destroy(InternLogbook $intern_logbook)
    {
        // Otorisasi: Cek apakah user adalah admin ATAU pemilik logbook.
        if (!Auth::user()->hasRole('admin') && Auth::id() !== $intern_logbook->user_id) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus logbook ini.');
        }
        
        $intern_logbook->delete();

        // Redirect ke halaman yang sesuai tergantung role
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.logbooks.index')->with('status', 'Logbook berhasil dihapus!');
        }

        return redirect()->route('student.logbooks.index')->with('status', 'Logbook berhasil dihapus!');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN METHODS (Melihat & Menyetujui Logbook)
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan SEMUA logbook untuk Admin.
     */
    public function indexAdmin()
    {
        $query = InternLogbook::with('user');

        // Search functionality
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('activity', 'like', "%{$search}%")
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('username', 'like', "%{$search}%");
                });
            });
        }

        // Filter by status
        if (request('status')) {
            $status = request('status');
            if ($status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Order by latest and paginate
        $logbooks = $query->latest()->paginate(15)->appends(request()->query());

        return view('admin.logbooks.index', compact('logbooks'));
    }

    /**
     * Menampilkan detail logbook (untuk review Admin).
     * PERBAIKAN #1: Nama variabel diubah menjadi $intern_logbook
     */
    public function show(InternLogbook $intern_logbook)
    {
        // Ubah nama variabel yang dikirim ke view agar konsisten
        return view('admin.logbooks.show', ['logbook' => $intern_logbook]);
    }

    /**
     * Aksi untuk menyetujui atau batal menyetujui logbook (Admin).
     * PERBAIKAN #1: Nama variabel diubah menjadi $intern_logbook
     * PERBAIKAN #3: Logika diubah menjadi toggle (approve/unapprove)
     */
    public function approve(InternLogbook $intern_logbook)
    {
        // Toggle status is_approved
        $newStatus = !$intern_logbook->is_approved;
        $intern_logbook->update(['is_approved' => $newStatus]);

        $message = $newStatus ? 'Logbook berhasil disetujui!' : 'Persetujuan logbook berhasil dibatalkan!';

        return back()->with('status', $message);
    }
}
