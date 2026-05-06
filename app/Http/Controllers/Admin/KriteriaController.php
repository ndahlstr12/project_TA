<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::orderBy('kode', 'asc')->get();
        $totalBobot = $kriterias->sum('bobot');
        return view('admin.spk.kriteria.index', compact('kriterias', 'totalBobot'));
    }

    public function create()
    {
        return view('admin.spk.kriteria.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:kriterias,kode',
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0',
            'jenis' => 'required|in:benefit,cost',
        ]);

        Kriteria::create($validated);

        return redirect()->route('admin.kriteria.index')->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit(Kriteria $kriterium)
    {
        return view('admin.spk.kriteria.edit', ['kriteria' => $kriterium]);
    }

    public function update(Request $request, Kriteria $kriterium)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:kriterias,kode,' . $kriterium->id,
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0',
            'jenis' => 'required|in:benefit,cost',
        ]);

        $kriterium->update($validated);

        return redirect()->route('admin.kriteria.index')->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(Kriteria $kriterium)
    {
        $kriterium->delete();
        return redirect()->route('admin.kriteria.index')->with('success', 'Kriteria berhasil dihapus.');
    }
}
