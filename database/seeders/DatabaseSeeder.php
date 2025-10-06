<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. PASTIKAN ROLES DIBUAT TERLEBIH DAHULU
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        // 2. BUAT AKUN ADMIN (1 ORANG)
        $adminUsername = 'admin.mds'; // Membuat username yang konsisten
        User::firstOrCreate(
            ['email' => 'admin@mds.co'],
            [
                'name' => 'Administrator MDS',
                'username' => $adminUsername, // Username untuk Admin
                'password' => Hash::make('admin123'), // Password: admin123
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        )->assignRole($adminRole);


        // 3. BUAT AKUN STUDENT (6 ORANG)
        $students = [
            'alfath.tendri',
            'diko.daen',
            'agnes.sura',
            'aura.kirana',
            'juan.alvin',
            'satrio.beny',
        ];

        foreach ($students as $username) {
            // Password akan menjadi: (username)123
            $password = $username . '123'; 
            
            // Nama dibuat kapital untuk kerapian
            $name = Str::of($username)->replace('.', ' ')->title();

            $student = User::firstOrCreate(
                ['username' => $username], // Cek berdasarkan username
                [
                    'name' => $name,
                    'email' => $username . '@mds.com', // Email unik (tidak akan dipakai untuk login)
                    'password' => Hash::make($password), 
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            $student->assignRole($studentRole);
        }
        
        $this->command->info('Database Seeder Sukses!');
        $this->command->info('Admin Login: ' . $adminUsername . ' / admin123');
        $this->command->info('Student Login: alfath.tendri / alfath.tendri123');
    }
}