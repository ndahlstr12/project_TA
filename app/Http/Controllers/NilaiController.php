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

        // Ambil mata pelajaran yang diampu berdasarkan JADWAL
        // Kelompokkan berdasarkan mapel dan kelas agar tidak ganda jika ada jadwal di hari berbeda
        $guruMapels = \App\Models\Jadwal::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->get()
            ->unique(function ($item) {
                return $item->mapel_id . '-' . $item->kelas_id;
            });

        return view('guru.nilai.index', compact('guruMapels'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'guru_mapel_id' => 'required|exists:jadwals,id'
        ]);

        $jadwal = \App\Models\Jadwal::with(['mapel', 'kelas.siswas'])->findOrFail($request->guru_mapel_id);
        
        // Pastikan ini adalah jadwal milik guru tersebut
        if ($jadwal->guru_id !== Auth::user()->guru_id) {
            abort(403);
        }

        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));

        $siswas = $jadwal->kelas->siswas;
        
        // Ambil nilai yang sudah ada jika ada
        $existingNilai = Nilai::where('guru_id', $jadwal->guru_id)
            ->where('mapel', $jadwal->mapel->nama_mapel)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->whereIn('siswa_id', $siswas->pluck('id'))
            ->get()
            ->keyBy('siswa_id');

        return view('guru.nilai.create', compact('jadwal', 'siswas', 'existingNilai', 'semester', 'tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_mapel_id' => 'required|exists:jadwals,id',
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $jadwal = \App\Models\Jadwal::with('mapel')->findOrFail($request->guru_mapel_id);
        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));

        foreach ($request->nilai as $siswaId => $nilaiAngka) {
            if ($nilaiAngka === null) continue;

            Nilai::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'guru_id' => $jadwal->guru_id,
                    'mapel' => $jadwal->mapel->nama_mapel,
                    'semester' => $semester,
                    'tahun_ajaran' => $tahunAjaran,
                ],
                [
                    'nilai_angka' => $nilaiAngka
                ]
            );
        }

        return redirect()->route('shared.nilai.index')->with('success', 'Nilai berhasil disimpan.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'guru_mapel_id' => 'required|exists:jadwals,id',
            'file_excel' => 'required|file|mimes:csv,txt'
        ]);

        $jadwal = \App\Models\Jadwal::with('mapel')->findOrFail($request->guru_mapel_id);
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
                        'guru_id' => $jadwal->guru_id,
                        'mapel' => $jadwal->mapel->nama_mapel,
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
}
