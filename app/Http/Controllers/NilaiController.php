<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // 1. Ambil mata pelajaran yang diampu sendiri (berdasarkan JADWAL)
        $guruMapels = \App\Models\Jadwal::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->get()
            ->unique(function ($item) {
                return $item->mapel_id . '-' . $item->kelas_id;
            });

        // 2. Jika Wali Kelas, ambil semua mata pelajaran di kelas perwaliannya
        $monitoringMapels = collect();
        if ($guru->is_walikelas) {
            $kelas = \App\Models\Kelas::where('wali_id', $guru->id)->first();
            if ($kelas) {
                $monitoringMapels = \App\Models\Jadwal::with(['mapel', 'guru'])
                    ->where('kelas_id', $kelas->id)
                    ->where('guru_id', '!=', $guru->id) // Hindari duplikasi dengan yang diampu sendiri
                    ->get()
                    ->unique('mapel_id');
            }
        }

        return view('guru.nilai.index', compact('guruMapels', 'monitoringMapels'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'guru_mapel_id' => 'required|exists:jadwals,id'
        ]);

        $guruMapel = \App\Models\Jadwal::with(['mapel', 'kelas.siswas'])->findOrFail($request->guru_mapel_id);
        
        if ($guruMapel->guru_id !== Auth::user()->guru_id) {
            abort(403);
        }

        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));

        $siswas = $guruMapel->kelas->siswas;
        
        $existingNilai = Nilai::where('guru_id', $guruMapel->guru_id)
            ->where('mapel_id', $guruMapel->mapel_id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->whereIn('siswa_id', $siswas->pluck('id'))
            ->get()
            ->keyBy('siswa_id');

        return view('guru.nilai.create', compact('guruMapel', 'siswas', 'existingNilai', 'semester', 'tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_mapel_id' => 'required|exists:jadwals,id',
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
            'capaian' => 'nullable|array',
            'capaian.*' => 'nullable|string',
        ]);

        $guruMapel = \App\Models\Jadwal::findOrFail($request->guru_mapel_id);

        // Keamanan: Cek apakah jadwal ini milik guru yang login
        if ($guruMapel->guru_id !== Auth::user()->guru_id) {
            abort(403, 'Akses ditolak.');
        }

        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));

        foreach ($request->nilai as $siswaId => $nilaiAngka) {
            if ($nilaiAngka === null) continue;

            Nilai::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'guru_id' => $guruMapel->guru_id,
                    'mapel_id' => $guruMapel->mapel_id,
                    'semester' => $semester,
                    'tahun_ajaran' => $tahunAjaran,
                ],
                [
                    'nilai_angka' => $nilaiAngka,
                    'capaian_kompetensi' => $request->capaian[$siswaId] ?? null
                ]
            );
        }

        return redirect()->route('shared.nilai.index')->with('success', 'Nilai berhasil disimpan.');
    }

    public function updateKkm(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'kkm' => 'required|integer|min:0|max:100'
        ]);

        $guru = Auth::user()->guru;
        
        // Cek apakah guru mengampu mata pelajaran ini
        $isTeaching = \App\Models\Jadwal::where('guru_id', $guru->id)
            ->where('mapel_id', $request->mapel_id)
            ->exists();

        if (!$isTeaching) {
            return redirect()->back()->with('error', 'Anda tidak mengampu mata pelajaran ini.');
        }

        $mapel = \App\Models\Mapel::findOrFail($request->mapel_id);
        $mapel->update(['kkm' => $request->kkm]);

        return redirect()->back()->with('success', 'KKM ' . $mapel->nama_mapel . ' berhasil diperbarui menjadi ' . $request->kkm);
    }

    public function import(Request $request)
    {
        $request->validate([
            'guru_mapel_id' => 'required|exists:jadwals,id',
            'file_excel' => 'required|file|mimes:csv,txt'
        ]);

        $guruMapel = \App\Models\Jadwal::findOrFail($request->guru_mapel_id);
        
        if ($guruMapel->guru_id !== Auth::user()->guru_id) {
            abort(403);
        }

        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));

        $file = $request->file('file_excel');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $nisn = $data[0];
            $nilaiAngka = $data[2];

            $siswa = Siswa::where('nisn', $nisn)->first();
            if ($siswa && is_numeric($nilaiAngka)) {
                Nilai::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'guru_id' => $guruMapel->guru_id,
                        'mapel_id' => $guruMapel->mapel_id,
                        'semester' => $semester,
                        'tahun_ajaran' => $tahunAjaran,
                    ],
                    [
                        'nilai_angka' => $nilaiAngka
                    ]
                );
            }
        }

        fclose($handle);

        return redirect()->route('shared.nilai.index')->with('success', 'Nilai berhasil diimport dari CSV.');
    }

    public function showMonitoring($jadwal_id)
    {
        $jadwal = \App\Models\Jadwal::with(['mapel', 'kelas.siswas', 'guru'])->findOrFail($jadwal_id);
        $user = Auth::user();
        $guru = $user->guru;

        // Pastikan yang mengakses adalah Wali Kelas dari kelas tersebut
        if ($jadwal->kelas->wali_id !== $guru->id) {
            abort(403, 'Anda bukan Wali Kelas dari kelas ini.');
        }

        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));

        $siswas = $jadwal->kelas->siswas;
        $nilais = Nilai::where('mapel_id', $jadwal->mapel_id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->whereIn('siswa_id', $siswas->pluck('id'))
            ->get()
            ->keyBy('siswa_id');

        return view('guru.nilai.monitoring', compact('jadwal', 'siswas', 'nilais', 'semester', 'tahunAjaran'));
    }
}
