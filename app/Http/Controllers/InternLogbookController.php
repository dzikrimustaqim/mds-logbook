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
     * Route: /logbooks
     */
    public function index()
    {
        $logbooks = Auth::user()->logbooks()->latest()->paginate(10);
        return view('logbooks.index', compact('logbooks'));
    }

    /**
     * Menampilkan form untuk membuat logbook baru.
     * Route: /logbooks/create
     */
    public function create()
    {
        return view('logbooks.create');
    }

    /**
     * Menyimpan logbook baru.
     * Route: POST /logbooks
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'title' => ['nullable', 'string', 'max:255'],
            'activity' => ['required', 'string'],
        ]);

        Auth::user()->logbooks()->create($validated);

        return redirect()->route('logbooks.index')->with('status', 'Logbook berhasil disimpan!');
    }

    /**
     * Menampilkan form untuk mengedit logbook.
     * Route: /logbooks/{logbook}/edit
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
     * Route: PATCH/PUT /logbooks/{logbook}
     */
    public function update(Request $request, InternLogbook $logbook)
    {
        // Otorisasi: Pastikan student hanya bisa update logbook miliknya.
        if (Auth::id() !== $logbook->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            // Rule::unique di sini opsional, tapi berguna jika ingin memastikan hanya satu logbook per hari per user.
            'date' => ['required', 'date'], 
            'title' => ['nullable', 'string', 'max:255'],
            'activity' => ['required', 'string'],
        ]);

        $logbook->update($validated);

        return redirect()->route('logbooks.index')->with('status', 'Logbook berhasil diperbarui!');
    }

    /**
     * Menghapus logbook.
     * Route: DELETE /logbooks/{logbook}
     */
    public function destroy(InternLogbook $logbook)
    {
        // Otorisasi: Pastikan student hanya bisa hapus logbook miliknya.
        if (Auth::id() !== $logbook->user_id) {
            abort(403);
        }
        
        $logbook->delete();

        return redirect()->route('logbooks.index')->with('status', 'Logbook berhasil dihapus!');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN METHODS (Melihat & Menyetujui Logbook)
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan SEMUA logbook untuk Admin.
     * Route: admin/logbooks (Menggunakan route admin.logbooks.index)
     */
    public function indexAdmin()
    {
        // Ambil semua logbook, urutkan berdasarkan yang terbaru dan sertakan data user (student)
        $logbooks = InternLogbook::with('user')->latest()->paginate(15);

        return view('admin.logbooks.index', compact('logbooks'));
    }

    /**
     * Menampilkan detail logbook (untuk review Admin).
     * Route: admin/logbooks/{logbook}
     */
    public function show(InternLogbook $logbook)
    {
        // Karena ini adalah show method, kita akan buat view khusus Admin untuk detail review
        return view('admin.logbooks.show', compact('logbook'));
    }

    /**
     * Aksi untuk menyetujui logbook (Admin).
     * Route: PATCH admin/logbooks/{logbook}/approve
     */
    public function approve(InternLogbook $logbook)
    {
        // Cek jika sudah disetujui, hindari update berulang
        if ($logbook->is_approved) {
             return back()->with('warning', 'Logbook ini sudah disetujui sebelumnya.');
        }
        
        $logbook->update(['is_approved' => true]);

        return back()->with('status', 'Logbook berhasil disetujui!');
    }
}