<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Simulasi data untuk laporan yang digabung ke dashboard
        $stats = [
            'total_siswa' => \App\Models\User::where('role', 'siswa')->count(),
            'total_guru' => \App\Models\User::where('role', 'guru')->count(),
            'total_kriteria' => \App\Models\Kriteria::count(),
            'rata_rata_nilai' => 84.2,
            'kehadiran_rata' => '96%',
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
