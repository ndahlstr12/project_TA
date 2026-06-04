<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $kelasFilter = $request->query('kelas_id');
        
        $query = Siswa::with('kelas');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($kelasFilter) {
            $query->where('kelas_id', $kelasFilter);
        }

        $siswas = $query->latest()->paginate(10)->withQueryString();
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();
        
        return view('admin.siswas.index', compact('siswas', 'kelasList'));
    }

    public function create()
    {
        $kelas = \App\Models\Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswas.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|unique:siswas,nisn|size:10',
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
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
        $kelas = \App\Models\Kelas::orderBy('nama_kelas')->get();
        return view('admin.siswas.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|size:10|unique:siswas,nisn,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
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
        $filePath = $file->getRealPath();
        
        $firstLine = file_get_contents($filePath, false, null, 0, 1000);
        $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';

        // OPTIMASI: Ambil semua kelas sekaligus
        $kelasMap = \App\Models\Kelas::pluck('id', 'nama_kelas')->all();
        
        // OPTIMASI: Ambil semua ID siswa yang sudah punya akun
        $existingStudentAccounts = \App\Models\User::where('role', 'siswa')->whereNotNull('siswa_id')->pluck('siswa_id', 'siswa_id')->all();
        $existingParentAccounts = \App\Models\User::where('role', 'orangtua')->whereNotNull('siswa_id')->pluck('siswa_id', 'siswa_id')->all();

        $handle = fopen($filePath, 'r');
        fgetcsv($handle, 1000, $delimiter); // Skip header

        $count = 0;
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if (count($data) >= 2) {
                    $nisn = trim($data[0]);
                    $nama = trim($data[1]);
                    $namaKelas = isset($data[2]) ? trim($data[2]) : null;

                    if (empty($nisn) || empty($nama)) continue;

                    // Lookup Kelas dari memori (sangat cepat)
                    $kelasId = $kelasMap[$namaKelas] ?? null;

                    // OTOMATIS: Buat kelas jika belum ada
                    if ($namaKelas && !$kelasId) {
                        // Parsing sederhana untuk Tingkat dan Jurusan (Contoh: X-AKL-1 atau XII RPL 1)
                        $parts = preg_split('/[- ]/', $namaKelas);
                        $tingkat = $parts[0] ?? 'X';
                        $jurusan = $parts[1] ?? 'Umum';

                        $newKelas = \App\Models\Kelas::create([
                            'nama_kelas' => $namaKelas,
                            'tingkat' => $tingkat,
                            'jurusan' => $jurusan
                        ]);
                        $kelasId = $newKelas->id;
                        $kelasMap[$namaKelas] = $kelasId; // Update map memory
                    }

                    // 1. Simpan/Update Data Siswa
                    $siswa = Siswa::updateOrCreate(
                        ['nisn' => $nisn],
                        ['nama' => $nama, 'kelas_id' => $kelasId]
                    );

                    // 2. Akun Siswa (Cek dari memori)
                    if (!isset($existingStudentAccounts[$siswa->id])) {
                        \App\Models\User::create([
                            'siswa_id' => $siswa->id,
                            'role' => 'siswa',
                            'name' => $nama,
                            'password' => \Illuminate\Support\Facades\Hash::make($nisn),
                        ]);
                        $existingStudentAccounts[$siswa->id] = $siswa->id;
                    }

                    // 3. Akun Orang Tua (Cek dari memori)
                    if (!isset($existingParentAccounts[$siswa->id])) {
                        \App\Models\User::create([
                            'siswa_id' => $siswa->id,
                            'role' => 'orangtua',
                            'name' => 'Orang Tua ' . $nama,
                            'password' => \Illuminate\Support\Facades\Hash::make('ortu' . $nisn),
                        ]);
                        $existingParentAccounts[$siswa->id] = $siswa->id;
                    }

                    $count++;
                }
            }
            \Illuminate\Support\Facades\DB::commit();
            fclose($handle);
            return back()->with('success', "$count data siswa dan akun login berhasil diimport.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            if (isset($handle)) fclose($handle);
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}
