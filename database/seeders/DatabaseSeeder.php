<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat Admin
        User::create([
            'name' => 'Administrator SMKN 1 Sungailiat',
            'email' => 'admin@smkn1sungailiat.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Buat Guru (Contoh)
        User::create([
            'name' => 'Guru Contoh',
            'email' => 'guru@example.com',
            'password' => Hash::make('password123'),
            'role' => 'guru',
        ]);

        // Buat Siswa (Contoh)
        User::create([
            'name' => 'Siswa Contoh',
            'email' => 'siswa@example.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
        ]);
    }
}
