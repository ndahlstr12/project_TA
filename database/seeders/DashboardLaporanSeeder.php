<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Kriteria;

class DashboardLaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('DASHBOARD_LAPORAN')->truncate();
        
        $totalGuru = User::where('role', 'guru')->count();
        $totalWali = User::where('role', 'walikelas')->count();

        DB::table('DASHBOARD_LAPORAN')->insert([
            'total_siswa' => User::where('role', 'siswa')->count(),
            'total_guru' => $totalGuru + $totalWali,
            'total_kriteria' => Kriteria::count(),
            'rata_rata_nilai' => 85.50,
            'kehadiran_rata' => 97.20,
            
            // Simulasi monitoring
            'guru_mapel_total' => $totalGuru,
            'guru_mapel_selesai' => max(0, $totalGuru - 2), // Simulasi 2 guru belum upload
            'walikelas_total' => $totalWali,
            'walikelas_selesai' => max(0, $totalWali - 1), // Simulasi 1 wali belum isi raport
            
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
