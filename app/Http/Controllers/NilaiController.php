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

        $guruMapels = GuruMapel::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->get();

        return view('guru.nilai.index', compact('guruMapels'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'guru_mapel_id' => 'required|exists:guru_mapel,id'
        ]);

        $guruMapel = GuruMapel::with(['mapel', 'kelas.siswas'])->findOrFail($request->guru_mapel_id);
        
        // Pastikan ini adalah mapel milik guru tersebut
        if ($guruMapel->guru_id !== Auth::user()->guru_id) {
            abort(403);
        }

        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));

        $siswas = $guruMapel->kelas->siswas;
        
        // Ambil nilai yang sudah ada jika ada
        $existingNilai = Nilai::where('guru_id', $guruMapel->guru_id)
            ->where('mapel', $guruMapel->mapel->nama_mapel)
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
            'guru_mapel_id' => 'required|exists:guru_mapel,id',
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $guruMapel = GuruMapel::with('mapel')->findOrFail($request->guru_mapel_id);
        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));

        foreach ($request->nilai as $siswaId => $nilaiAngka) {
            if ($nilaiAngka === null) continue;

            Nilai::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'guru_id' => $guruMapel->guru_id,
                    'mapel' => $guruMapel->mapel->nama_mapel,
                    'semester' => $semester,
                    'tahun_ajaran' => $tahunAjaran,
                ],
                [
                    'nilai_angka' => $nilaiAngka
                ]
            );
        }

        return redirect()->route('guru.nilai.index')->with('success', 'Nilai berhasil disimpan.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'guru_mapel_id' => 'required|exists:guru_mapel,id',
            'file_excel' => 'required|file|mimes:csv,txt' // Sementara CSV untuk kemudahan tanpa library tambahan
        ]);

        $guruMapel = GuruMapel::with('mapel')->findOrFail($request->guru_mapel_id);
        $semester = Setting::get('semester', 'Ganjil');
        $tahunAjaran = Setting::get('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));

        $file = $request->file('file_excel');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Asumsi format: NISN, Nama, Nilai
            $nisn = $data[0];
            $nilaiAngka = $data[2];

            $siswa = Siswa::where('nisn', $nisn)->first();
            if ($siswa && is_numeric($nilaiAngka)) {
                Nilai::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'guru_id' => $guruMapel->guru_id,
                        'mapel' => $guruMapel->mapel->nama_mapel,
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

        return redirect()->route('guru.nilai.index')->with('success', 'Nilai berhasil diimport dari CSV.');
    }
}
