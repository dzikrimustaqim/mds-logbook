<?php

namespace App\Models;

// Impor Trait HasRoles dari Spatie
use Spatie\Permission\Traits\HasRoles; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Tambahkan ini jika Anda menggunakan Laravel Sanctum atau API
use Laravel\Sanctum\HasApiTokens; 
// Impor relasi HasMany
use Illuminate\Database\Eloquent\Relations\HasMany; 

class User extends Authenticatable
{
    // Tambahkan HasApiTokens dan HasRoles
    use HasFactory, Notifiable, HasApiTokens, HasRoles; 

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==========================================================
    // RELASI MODEL
    // ==========================================================

    /**
     * Relasi: Mendapatkan semua logbook magang milik pengguna ini.
     * Ini penting untuk role Student.
     */
    public function logbooks(): HasMany
    {
        // Hubungkan ke model Logbook (kita akan buat model ini nanti!)
        return $this->hasMany(InternLogbook::class);
    }
}