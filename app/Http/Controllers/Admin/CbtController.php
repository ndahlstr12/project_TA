<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CbtUjian;
use App\Models\CbtSoal;
use App\Imports\CbtSoalImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CbtController extends Controller
{
    // 1. Daftar Semua Paket Ujian (Halaman Utama Bank Soal)
    public function index()
    {
        $user = auth()->user();
        $query = CbtUjian::withCount('soals')->latest();

        // Jika bukan admin, filter berdasarkan mata pelajaran yang diampu guru
        if ($user->role !== 'admin') {
            $guru = $user->guru;
            if ($guru) {
                $mapelNames = $guru->mapels()->with('mapel')->get()->pluck('mapel.nama_mapel')->unique();
                $query->whereIn('mapel', $mapelNames);
            } else {
                // Jika user tidak punya profil guru (dan bukan admin), jangan tampilkan apapun
                $query->whereRaw('1 = 0');
            }
        }

        $ujians = $query->get();
        return view('guru.cbt.index', compact('ujians'));
    }

    // 2. Form Buat Paket Ujian Baru (Sekaligus Pengaturan)
    public function create()
    {
        $user = auth()->user();
        $mapels = collect();
        $kelas = collect();

        if ($user->role !== 'admin' && $user->guru) {
            $guru = $user->guru;
            $mapels = $guru->mapels()->with('mapel')->get()->pluck('mapel.nama_mapel')->unique();
            $kelas = $guru->mapels()->with('kelas')->get()->pluck('kelas.nama_kelas')->unique();
        } else {
            // Untuk admin, ambil semua data (atau sesuaikan kebutuhan)
            $mapels = \App\Models\Mapel::pluck('nama_mapel');
            $kelas = \App\Models\Kelas::pluck('nama_kelas');
        }

        return view('guru.cbt.create', compact('mapels', 'kelas'));
    }

    // 3. Simpan Paket Ujian
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ujian' => 'required|string|max:255',
            'mapel' => 'required|string',
            'kelas' => 'required|string',
            'level' => 'required|in:Mudah,Sedang,Sulit',
            'durasi' => 'required|integer|min:1',
            'jumlah_soal' => 'required|integer|min:1',
        ]);

        $validated['acak_soal'] = $request->has('acak_soal');
        $validated['acak_jawaban'] = $request->has('acak_jawaban');
        $validated['status'] = true;

        $ujian = CbtUjian::create($validated);

        return redirect()->route('shared.cbt.show', $ujian->id)->with('success', 'Paket Ujian berhasil dibuat. Silakan tambahkan soal.');
    }

    // 4. Detail Paket Ujian & Daftar Soal di Dalamnya
    public function show($id)
    {
        $ujian = CbtUjian::with('soals')->findOrFail($id);
        return view('guru.cbt.show', compact('ujian'));
    }

    // 5. Simpan Soal Manual Ke Dalam Paket Ujian
    public function storeSoal(Request $request, $ujian_id)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required',
            'opsi_a' => 'required',
            'opsi_b' => 'required',
            'opsi_c' => 'required',
            'opsi_d' => 'required',
            'opsi_e' => 'nullable',
            'jawaban_benar' => 'required|in:A,B,C,D,E',
        ]);

        $validated['ujian_id'] = $ujian_id;
        
        CbtSoal::create($validated);

        return back()->with('success', 'Soal berhasil ditambahkan ke paket ujian.');
    }

    // 6. Import Soal Excel Ke Dalam Paket Ujian
    public function import(Request $request, $ujian_id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new CbtSoalImport($ujian_id), $request->file('file'));
            return back()->with('success', 'Data soal berhasil diimport ke paket ujian.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport soal: ' . $e->getMessage());
        }
    }

    // 7. Toggle Status Ujian (Aktif/Nonaktif)
    public function toggleStatus($id)
    {
        $ujian = CbtUjian::findOrFail($id);
        $ujian->status = !$ujian->status;
        $ujian->save();

        return back()->with('success', 'Status ujian berhasil diubah.');
    }
}
