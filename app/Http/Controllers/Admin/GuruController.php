<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::latest()->paginate(10);
        return view('admin.gurus.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.gurus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|unique:gurus,nip',
            'nama' => 'required|string|max:255',
            'gelar' => 'nullable|string|max:50',
            'spesialisasi' => 'nullable|string|max:100',
            'role' => 'required|in:guru,walikelas',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Simpan Data Guru
            $guru = Guru::create([
                'nip' => $validated['nip'],
                'nama' => $validated['nama'],
                'gelar' => $validated['gelar'],
                'spesialisasi' => $validated['spesialisasi'],
            ]);

            // 2. Buat Akun Login Otomatis
            \App\Models\User::create([
                'name' => $guru->nama . ($guru->gelar ? ', ' . $guru->gelar : ''),
                'email' => null, 
                'password' => \Illuminate\Support\Facades\Hash::make($guru->nip),
                'role' => $validated['role'], // Menggunakan role dari form (guru atau walikelas)
                'guru_id' => $guru->id,
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.gurus.index')->with('success', 'Data guru dan akun login berhasil dibuat.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit(Guru $guru)
    {
        return view('admin.gurus.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nip' => 'required|string|unique:gurus,nip,' . $guru->id,
            'nama' => 'required|string|max:255',
            'gelar' => 'nullable|string|max:50',
            'spesialisasi' => 'nullable|string|max:100',
        ]);

        $guru->update($validated);

        return redirect()->route('admin.gurus.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        return redirect()->route('admin.gurus.index')->with('success', 'Data guru berhasil dihapus.');
    }
}
