<?php

namespace App\Http\Controllers;

use App\Models\InternLogbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index(Request $request)
    {
        $query = Auth::user()->logbooks();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('activity', 'like', "%{$request->search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('is_approved', $request->status === 'approved');
        }

        $logbooks = $query->latest()->paginate(10)->appends($request->query());

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

        return redirect()->route('logbooks.index')
            ->with('success', 'Logbook berhasil disimpan!');
    }

    /**
     * Menampilkan detail logbook.
     */
    public function show(InternLogbook $logbook)
    {
        // Otorisasi: Student hanya bisa lihat logbook miliknya
        if (Auth::user()->hasRole('student') && Auth::id() !== $logbook->user_id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat logbook ini.');
        }

        return view('logbooks.show', compact('logbook'));
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

        return redirect()->route('logbooks.index')
            ->with('success', 'Logbook berhasil diperbarui!');
    }

    /**
     * Menghapus logbook.
     */
    public function destroy(InternLogbook $logbook, InternLogbook $intern_logbook = null)
    {
        // Use the appropriate parameter based on which route called this
        $targetLogbook = $intern_logbook ?? $logbook;
        
        // Otorisasi: Cek apakah user adalah admin ATAU pemilik logbook.
        if (!Auth::user()->hasRole('admin') && Auth::id() !== $targetLogbook->user_id) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus logbook ini.');
        }
        
        $targetLogbook->delete();

        // Redirect ke halaman yang sesuai tergantung role
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.logbooks.index')
                ->with('success', 'Logbook berhasil dihapus!');
        }

        return redirect()->route('logbooks.index')
            ->with('success', 'Logbook berhasil dihapus!');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN METHODS (Melihat & Menyetujui Logbook)
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan SEMUA logbook untuk Admin.
     */
    public function indexAdmin(Request $request)
    {
        $query = InternLogbook::with('user');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('activity', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function ($userQuery) use ($request) {
                      $userQuery->where('name', 'like', "%{$request->search}%")
                                ->orWhere('username', 'like', "%{$request->search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('is_approved', $request->status === 'approved');
        }

        // Order by latest and paginate
        $logbooks = $query->latest()->paginate(15)->appends($request->query());

        return view('admin.logbooks.index', compact('logbooks'));
    }

    /**
     * Aksi untuk menyetujui atau batal menyetujui logbook (Admin).
     */
    public function approve(InternLogbook $intern_logbook)
    {
        // Toggle status is_approved
        $newStatus = !$intern_logbook->is_approved;
        $intern_logbook->update(['is_approved' => $newStatus]);

        $message = $newStatus 
            ? 'Logbook berhasil disetujui!' 
            : 'Persetujuan logbook berhasil dibatalkan!';

        return back()->with('success', $message);
    }
}