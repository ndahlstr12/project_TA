<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Simulasi data untuk laporan
        $stats = [
            'total_siswa' => User::where('role', 'siswa')->count(),
            'total_guru' => User::where('role', 'guru')->count(),
            'total_kriteria' => Kriteria::count(),
            'rata_rata_nilai' => 82.5, // Contoh dummy
            'kehadiran_rata' => '94%', // Contoh dummy
        ];

        return view('admin.reports.index', compact('stats'));
    }
}
