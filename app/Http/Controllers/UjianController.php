<?php

namespace App\Http\Controllers;

use App\Models\CbtUjian;
use App\Models\CbtSoal;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    public function index()
    {
        $ujians = CbtUjian::latest()->get();
        return view('guru.ujian.index', compact('ujians'));
    }

    public function create()
    {
        return view('guru.ujian.create');
    }

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
        $validated['status'] = true; // Langsung aktif saat dibuat

        CbtUjian::create($validated);

        return redirect()->route('guru.ujian.index')->with('success', 'Sesi ujian berhasil dibuat dan diaktifkan.');
    }

    public function toggleStatus($id)
    {
        $ujian = CbtUjian::findOrFail($id);
        $ujian->status = !$ujian->status;
        $ujian->save();

        return back()->with('success', 'Status ujian berhasil diubah.');
    }
}
