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
        // Logika import excel
        return back()->with('success', 'Jadwal berhasil diimport.');
    }
}
