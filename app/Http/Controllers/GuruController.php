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
        $guru = $user->guru; // Mengambil relasi guru dari model User

        if (!$guru) {
            return view('guru.dashboard', [
                'schedules' => collect(),
                'totalKelas' => 0,
                'totalSiswa' => 0,
                'studentsInClasses' => collect(),
                'isWaliKelas' => false,
                'waliKelasSiswa' => collect(),
                'namaKelasWali' => null,
                'kelasSummary' => collect()
            ]);
        }

        // Ambil jadwal mengajar guru ini menggunakan guru_id
        $schedules = Jadwal::with(['mapel', 'kelas'])->where('guru_id', $guru->id)->get();

        // Ambil ID kelas-kelas yang diajar
        $kelasIds = $schedules->pluck('kelas_id')->unique();
        $totalKelas = $kelasIds->count();

        // Ambil data siswa dari kelas-kelas yang diajar menggunakan kelas_id
        $studentsInClasses = Siswa::with('kelas')->whereIn('kelas_id', $kelasIds)->get();
        $totalSiswa = $studentsInClasses->count();

        // Summary per kelas
        $kelasSummary = $studentsInClasses->groupBy('kelas_id')->map(function ($items) {
            $kelas = $items->first()->kelas;
            return [
                'nama_kelas' => $kelas ? $kelas->nama_kelas : 'Tidak Diketahui',
                'jumlah_siswa' => $items->count(),
            ];
        });

        // Cek jika guru adalah wali kelas
        $isWaliKelas = $guru->is_walikelas;
        $waliKelasSiswa = collect();
        $namaKelasWali = null;
        
        if ($isWaliKelas && $guru->kelas_id) {
            $kelasWali = \App\Models\Kelas::find($guru->kelas_id);
            $namaKelasWali = $kelasWali ? $kelasWali->nama_kelas : null;
            $waliKelasSiswa = Siswa::where('kelas_id', $guru->kelas_id)->get();
        }

        return view('guru.dashboard', compact(
            'schedules', 
            'totalKelas', 
            'totalSiswa', 
            'studentsInClasses',
            'isWaliKelas', 
            'waliKelasSiswa',
            'namaKelasWali',
            'kelasSummary'
        ));
    }
}
