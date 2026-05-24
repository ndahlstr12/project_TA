<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with(['guru', 'mapel', 'kelas'])->latest()->get();
        $mapels = \App\Models\Mapel::orderBy('nama_mapel')->get();
        $gurus = \App\Models\Guru::orderBy('nama')->get();
        $kelas = \App\Models\Kelas::orderBy('nama_kelas')->get();
        
        return view('admin.jadwal.index', compact('jadwals', 'mapels', 'gurus', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'mapel_nama' => 'required|string|max:255',
            'guru_id' => 'required|exists:gurus,id',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        // Cari atau buat Mapel secara otomatis
        $mapel = \App\Models\Mapel::firstOrCreate(
            ['nama_mapel' => $request->mapel_nama],
            ['kode_mapel' => strtoupper(substr($request->mapel_nama, 0, 3)) . rand(100, 999)]
        );

        Jadwal::create([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'mapel_id' => $mapel->id,
            'guru_id' => $request->guru_id,
            'kelas_id' => $request->kelas_id,
        ]);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Lewati header
        fgetcsv($handle);

        $count = 0;
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if (count($data) >= 6) {
                // Cari atau buat Mapel
                $mapel = \App\Models\Mapel::firstOrCreate(
                    ['nama_mapel' => $data[3]],
                    ['kode_mapel' => strtoupper(substr($data[3], 0, 3)) . rand(100, 999)]
                );

                // Cari atau buat Guru (berdasarkan nama jika NIP tidak ada di CSV)
                $guru = \App\Models\Guru::firstOrCreate(
                    ['nama' => $data[4]],
                    ['nip' => 'TEMP' . rand(10000, 99999)]
                );

                // Cari atau buat Kelas
                $kelas = \App\Models\Kelas::firstOrCreate(
                    ['nama_kelas' => $data[5]],
                    ['tingkat' => 'X', 'jurusan' => 'Umum']
                );

                Jadwal::create([
                    'hari' => $data[0],
                    'jam_mulai' => $data[1],
                    'jam_selesai' => $data[2],
                    'mapel_id' => $mapel->id,
                    'guru_id' => $guru->id,
                    'kelas_id' => $kelas->id,
                ]);
                $count++;
            }
        }

        fclose($handle);

        return back()->with('success', $count . ' Jadwal berhasil diimport dan disinkronkan dengan data Master.');
    }
}
