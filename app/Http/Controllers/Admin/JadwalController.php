<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::latest()->get();
        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'mapel' => 'required',
            'guru' => 'required',
            'kelas' => 'required',
        ]);

        Jadwal::create($validated);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Lewati header jika ada
        fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if (count($data) >= 6) {
                Jadwal::create([
                    'hari' => $data[0],
                    'jam_mulai' => $data[1],
                    'jam_selesai' => $data[2],
                    'mapel' => $data[3],
                    'guru' => $data[4],
                    'kelas' => $data[5],
                ]);
            }
        }

        fclose($handle);

        return back()->with('success', 'Jadwal berhasil diimport dari file CSV.');
    }
}
