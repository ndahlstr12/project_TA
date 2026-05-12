<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kriteria;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Mengambil data ringkas dari tabel DASHBOARD_LAPORAN
        $reportData = DB::table('DASHBOARD_LAPORAN')->first();

        $stats = [
            'total_siswa' => $reportData->total_siswa ?? User::where('role', 'siswa')->count(),
            'total_guru' => $reportData->total_guru ?? User::where('role', 'guru')->count(),
            'total_kriteria' => $reportData->total_kriteria ?? Kriteria::count(),
            'rata_rata_nilai' => $reportData->rata_rata_nilai ?? 0,
            'kehadiran_rata' => ($reportData->kehadiran_rata ?? 0) . '%',
        ];

        return view('admin.reports.index', compact('stats'));
    }
}
