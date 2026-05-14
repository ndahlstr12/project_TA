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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Lewati header (NISN, Nama, Kelas, JK)
        fgetcsv($handle);

        $count = 0;
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if (count($data) >= 4) {
                    $nisn = $data[0];
                    $nama = $data[1];
                    $kelas = $data[2];
                    $jk = strtoupper($data[3]); // L/P

                    // 1. Simpan/Update Data Siswa
                    $siswa = Siswa::updateOrCreate(
                        ['nisn' => $nisn],
                        ['nama' => $nama, 'kelas' => $kelas, 'jenis_kelamin' => $jk]
                    );

                    // 2. Buat/Update Akun Siswa
                    \App\Models\User::updateOrCreate(
                        ['siswa_id' => $siswa->id, 'role' => 'siswa'],
                        [
                            'name' => $nama,
                            'password' => \Illuminate\Support\Facades\Hash::make($nisn),
                        ]
                    );

                    // 3. Buat/Update Akun Orang Tua
                    \App\Models\User::updateOrCreate(
                        ['siswa_id' => $siswa->id, 'role' => 'orangtua'],
                        [
                            'name' => 'Orang Tua ' . $nama,
                            'password' => \Illuminate\Support\Facades\Hash::make('ortu' . $nisn),
                        ]
                    );

                    $count++;
                }
            }
            \Illuminate\Support\Facades\DB::commit();
            fclose($handle);
            return back()->with('success', "$count data siswa dan akun login berhasil diimport.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            fclose($handle);
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
