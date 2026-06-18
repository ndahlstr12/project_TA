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

        $jadwalId = $request->input('jadwal_id');
        
        // Ambil jadwal yang diampu oleh guru ini
        $allJadwal = \App\Models\Jadwal::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->get();
        
        $siswas = collect();
        $selectedJadwal = null;
        $kelasId = null;

        if ($jadwalId) {
            $selectedJadwal = \App\Models\Jadwal::with('kelas')->findOrFail($jadwalId);
            $kelasId = $selectedJadwal->kelas_id;
            $siswas = Siswa::where('kelas_id', $kelasId)->get();
            
            // Ambil data kehadiran hari ini untuk jadwal tersebut
            $today = Carbon::today()->toDateString();
            foreach ($siswas as $siswa) {
                $kehadiran = Kehadiran::where('siswa_id', $siswa->id)
                    ->where('jadwal_id', $jadwalId)
                    ->where('tanggal', $today)
                    ->first();
                $siswa->kehadiran_hari_ini = $kehadiran;
            }
        }

        return view('guru.kehadiran.index', compact('allJadwal', 'siswas', 'jadwalId', 'selectedJadwal', 'kelasId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'jadwal_id' => 'nullable|exists:jadwals,id',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpa',
            'menit_terlambat' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Keamanan: Cek apakah jadwal ini milik guru yang login
        if ($request->jadwal_id) {
            $jadwal = \App\Models\Jadwal::findOrFail($request->jadwal_id);
            if ($jadwal->guru_id !== Auth::user()->guru_id) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
            }
        }

        $today = Carbon::today()->toDateString();
        // Eager load kelas, waliKelas, dan user untuk akurasi data
        $siswa = Siswa::with(['kelas.waliKelas.user'])->findOrFail($request->siswa_id);

        $kehadiran = Kehadiran::updateOrCreate(
            [
                'siswa_id' => $request->siswa_id,
                'jadwal_id' => $request->jadwal_id,
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
                'type' => 'attendance',
                'penerima' => 'Wali Kelas'
            ]);
        }

        // 2. Notifikasi ke Orang Tua
        $parentUser = User::where('siswa_id', $siswa->id)->where('role', 'orangtua')->first();
        if ($parentUser) {
            Notification::create([
                'user_id' => $parentUser->id,
                'title' => $title,
                'message' => $message,
                'type' => 'attendance',
                'penerima' => 'Ortu'
            ]);
        }
    }
}
