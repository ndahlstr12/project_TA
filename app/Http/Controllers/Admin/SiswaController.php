<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::latest()->paginate(10);
        return view('admin.siswas.index', compact('siswas'));
    }

    public function create()
    {
        return view('admin.siswas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|unique:siswas,nisn|size:10',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Simpan Data Siswa
            $siswa = Siswa::create($validated);

            // 2. Buat Akun Login Siswa
            \App\Models\User::create([
                'name' => $siswa->nama,
                'email' => null, 
                'password' => \Illuminate\Support\Facades\Hash::make($siswa->nisn),
                'role' => 'siswa',
                'siswa_id' => $siswa->id,
            ]);

            // 3. Buat Akun Login Orang Tua
            \App\Models\User::create([
                'name' => 'Orang Tua ' . $siswa->nama,
                'email' => null,
                'password' => \Illuminate\Support\Facades\Hash::make('ortu' . $siswa->nisn), // Password default = ortu + NISN
                'role' => 'orangtua',
                'siswa_id' => $siswa->id,
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.siswas.index')->with('success', 'Data siswa, akun siswa, dan akun orang tua berhasil dibuat.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit(Siswa $siswa)
    {
        return view('admin.siswas.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|size:10|unique:siswas,nisn,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $siswa->update($validated);

        return redirect()->route('admin.siswas.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('admin.siswas.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
