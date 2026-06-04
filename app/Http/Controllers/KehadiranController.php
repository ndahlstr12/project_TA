<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use App\Models\Siswa;
use App\Models\Notification;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        $kelasId = $request->input('kelas_id');
        
        // OPTIMASI: Hanya tampilkan kelas yang diajar oleh guru ini (dari jadwal)
        // Ditambah kelas yang dia walikan
        $taughtKelasIds = \App\Models\Jadwal::where('guru_id', $guru->id)->pluck('kelas_id')->toArray();
        $supervisedKelasId = \App\Models\Kelas::where('wali_id', $guru->id)->pluck('id')->toArray();
        
        $relevantKelasIds = array_unique(array_merge($taughtKelasIds, $supervisedKelasId));
        
        $allKelas = \App\Models\Kelas::whereIn('id', $relevantKelasIds)
            ->orderBy('nama_kelas')
            ->get();
        
        $siswas = collect();
        if ($kelasId) {
            $siswas = Siswa::where('kelas_id', $kelasId)->get();
            
            // Ambil data kehadiran hari ini untuk kelas tersebut
            $today = Carbon::today()->toDateString();
            foreach ($siswas as $siswa) {
                $kehadiran = Kehadiran::where('siswa_id', $siswa->id)
                    ->where('tanggal', $today)
                    ->first();
                $siswa->kehadiran_hari_ini = $kehadiran;
            }
        }

        return view('guru.kehadiran.index', compact('allKelas', 'siswas', 'kelasId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpa',
            'menit_terlambat' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $today = Carbon::today()->toDateString();
        $siswa = Siswa::with('kelas')->findOrFail($request->siswa_id);

        $kehadiran = Kehadiran::updateOrCreate(
            [
                'siswa_id' => $request->siswa_id,
                'tanggal' => $today,
            ],
            [
                'status' => $request->status,
                'menit_terlambat' => $request->menit_terlambat ?? 0,
                'keterangan' => $request->keterangan,
            ]
        );

        // Kirim Notifikasi jika Sakit, Izin, Alpa, atau Terlambat
        if ($request->status !== 'Hadir' || ($request->menit_terlambat > 0)) {
            $this->sendNotifications($siswa, $kehadiran);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kehadiran berhasil dicatat.',
            'data' => $kehadiran
        ]);
    }

    public function batchStoreHadir(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $today = Carbon::today()->toDateString();
        $siswas = Siswa::where('kelas_id', $request->kelas_id)->get();
        $count = 0;

        foreach ($siswas as $siswa) {
            // Cek apakah sudah ada catatan hari ini
            $exists = Kehadiran::where('siswa_id', $siswa->id)
                ->where('tanggal', $today)
                ->exists();

            if (!$exists) {
                Kehadiran::create([
                    'siswa_id' => $siswa->id,
                    'tanggal' => $today,
                    'status' => 'Hadir',
                    'menit_terlambat' => 0,
                ]);
                $count++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil memproses {$count} siswa sebagai Hadir Tepat Waktu.",
        ]);
    }

    private function sendNotifications($siswa, $kehadiran)
    {
        $statusStr = $kehadiran->status;
        if ($kehadiran->status === 'Hadir' && $kehadiran->menit_terlambat > 0) {
            $statusStr = "Terlambat ({$kehadiran->menit_terlambat} menit)";
        }

        $namaKelas = $siswa->kelas ? $siswa->kelas->nama_kelas : 'Tidak Diketahui';
        $title = "Notifikasi Kehadiran: {$siswa->nama}";
        $message = "Siswa atas nama {$siswa->nama} (Kelas {$namaKelas}) tercatat {$statusStr} pada tanggal " . Carbon::parse($kehadiran->tanggal)->translatedFormat('d F Y') . ".";

        // 1. Notifikasi ke Wali Kelas
        $guruWali = null;
        if ($siswa->kelas && $siswa->kelas->wali_id) {
            $guruWali = $siswa->kelas->waliKelas;
        }

        if ($guruWali && $guruWali->user) {
            Notification::create([
                'user_id' => $guruWali->user->id,
                'title' => $title,
                'message' => $message,
                'type' => 'attendance'
            ]);
        }

        // 2. Notifikasi ke Orang Tua
        $userSiswa = User::where('siswa_id', $siswa->id)->where('role', 'siswa')->first();
        if ($userSiswa) {
            $parentUser = User::where('siswa_id', $siswa->id)->where('role', 'orangtua')->first();
            if ($parentUser) {
                Notification::create([
                    'user_id' => $parentUser->id,
                    'title' => $title,
                    'message' => $message,
                    'type' => 'attendance'
                ]);
            }
        }
    }
}
