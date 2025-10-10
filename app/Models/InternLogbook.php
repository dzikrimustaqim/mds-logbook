<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternLogbook extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     * Secara default, Laravel akan menggunakan 'logbooks'.
     */
    protected $table = 'logbooks';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'user_id',
        'date',
        'title',
        'activity',
        'is_approved',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     * Memastikan 'date' selalu berupa objek Carbon/Date.
     * Memastikan 'is_approved' selalu berupa boolean.
     */
    protected $casts = [
        'date' => 'date',
        'is_approved' => 'boolean',
    ];

    // ==========================================================
    // RELASI MODEL
    // ==========================================================

    /**
     * Relasi: Logbook ini dibuat oleh User (Student) yang mana.
     * Ini adalah relasi kunci untuk otorisasi (hanya Student yang bisa edit logbook-nya).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}