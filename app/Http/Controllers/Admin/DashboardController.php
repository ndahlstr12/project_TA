<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data ringkas dari tabel DASHBOARD_LAPORAN
        $reportData = DB::table('DASHBOARD_LAPORAN')->first();

        // Hitung progres real
        $guruMapelTotal = \App\Models\User::where('role', 'guru')->count();
        $guruMapelSelesai = \App\Models\Nilai::distinct('guru_id')->count();

        $walikelasTotal = \App\Models\User::where('role', 'walikelas')->count();
        $walikelasSelesai = \App\Models\Raport::where('status', 'selesai')->distinct('wali_id')->count();

        $stats = [
            'total_siswa' => $reportData->total_siswa ?? \App\Models\User::where('role', 'siswa')->count(),
            'total_guru' => $reportData->total_guru ?? \App\Models\User::where('role', 'guru')->count(),
            'total_kriteria' => $reportData->total_kriteria ?? \App\Models\Kriteria::count(),
            'rata_rata_nilai' => $reportData->rata_rata_nilai ?? 0,
            'kehadiran_rata' => ($reportData->kehadiran_rata ?? 0) . '%',
            
            // Progres Real
            'guru_mapel_total' => $guruMapelTotal,
            'guru_mapel_selesai' => $guruMapelSelesai,
            'walikelas_total' => $walikelasTotal,
            'walikelas_selesai' => $walikelasSelesai,
        ];

        // Ambil daftar guru dan wali kelas (Status REAL)
        $pendingTeachers = \App\Models\User::whereIn('role', ['guru', 'walikelas'])
            ->with('guru')
            ->latest()
            ->get()
            ->map(function($user) {
                if ($user->role === 'walikelas') {
                    $isSelesai = \App\Models\Raport::where('wali_id', $user->guru_id)
                        ->where('status', 'selesai')
                        ->exists();
                    $user->status_tugas = $isSelesai ? 'Selesai' : 'Pending';
                    $user->tipe_tugas = 'Input Raport';
                } else {
                    $isSelesai = \App\Models\Nilai::where('guru_id', $user->guru_id)->exists();
                    $user->status_tugas = $isSelesai ? 'Selesai' : 'Pending';
                    $user->tipe_tugas = 'Upload Nilai';
                }
                return $user;
            })
            ->take(5);

        return view('admin.dashboard', compact('stats', 'pendingTeachers'));
    }
}
