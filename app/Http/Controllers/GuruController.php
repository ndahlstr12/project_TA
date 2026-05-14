<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guruName = $user->name;

        // Ambil jadwal mengajar guru ini
        $schedules = Jadwal::where('guru', 'LIKE', '%' . $guruName . '%')->get();

        // Hitung total kelas unik
        $totalKelas = $schedules->pluck('kelas')->unique()->count();

        // Hitung total siswa dari kelas-kelas tersebut
        $kelasAmampu = $schedules->pluck('kelas')->unique();
        $totalSiswa = Siswa::whereIn('kelas', $kelasAmampu)->count();

        return view('guru.dashboard', compact('schedules', 'totalKelas', 'totalSiswa'));
    }
}
