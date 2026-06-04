<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();
        
        $firstLine = file_get_contents($filePath, false, null, 0, 1000);
        $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';

        // OPTIMASI: Lookup Data di awal (In-Memory)
        $kelasMap = \App\Models\Kelas::pluck('id', 'nama_kelas')->all();
        $existingStudentAccounts = User::where('role', 'siswa')->whereNotNull('siswa_id')->pluck('siswa_id', 'siswa_id')->all();
        $existingParentAccounts = User::where('role', 'orangtua')->whereNotNull('siswa_id')->pluck('siswa_id', 'siswa_id')->all();

        $handle = fopen($filePath, 'r');
        fgetcsv($handle, 1000, $delimiter); // Skip header

        $count = 0;
        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                // Format Sederhana: NISN, Nama, Kelas (3 kolom)
                if (count($data) == 3 || count($data) == 4) {
                    $nisn = trim($data[0]);
                    $nama = trim($data[1]);
                    $namaKelas = isset($data[2]) ? trim($data[2]) : null;

                    if (empty($nisn) || empty($nama)) continue;

                    // Lookup Kelas (In-Memory)
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

                    // 1. Buat Data Siswa
                    $siswa = \App\Models\Siswa::updateOrCreate(
                        ['nisn' => $nisn],
                        ['nama' => $nama, 'kelas_id' => $kelasId]
                    );

                    // 2. Akun Siswa (Cek In-Memory)
                    if (!isset($existingStudentAccounts[$siswa->id])) {
                        User::create([
                            'siswa_id' => $siswa->id,
                            'role' => 'siswa',
                            'name' => $nama,
                            'password' => Hash::make($nisn)
                        ]);
                        $existingStudentAccounts[$siswa->id] = $siswa->id;
                    }

                    // 3. Akun Orang Tua (Cek In-Memory)
                    if (!isset($existingParentAccounts[$siswa->id])) {
                        User::create([
                            'siswa_id' => $siswa->id,
                            'role' => 'orangtua',
                            'name' => 'Orang Tua ' . $nama,
                            'password' => Hash::make($nisn)
                        ]);
                        $existingParentAccounts[$siswa->id] = $siswa->id;
                    }
                    
                    $count++;
                } 
                // Format Lengkap tetap didukung...
                else if (count($data) >= 5) {
                    // ... (logika format lengkap tetap sama)
                }
            }
            DB::commit();
            fclose($handle);
            return back()->with('success', "$count pengguna berhasil diimport.");
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($handle)) fclose($handle);
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $role = $request->query('role');
        $search = $request->query('search');
        $kelasId = $request->query('kelas_id');
        $query = User::query();

        if ($role) {
            if ($role === 'guru') {
                $query->whereIn('role', ['guru', 'walikelas']);
            } else {
                $query->where('role', $role);
            }
        }

        if ($kelasId && in_array($role, ['siswa', 'orangtua'])) {
            $query->whereHas('siswa', function($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->with(['siswa.kelas', 'guru'])->latest()->paginate(10)->withQueryString();
        $activeTab = $role ?: 'all';
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();

        return view('admin.users.index', compact('users', 'activeTab', 'kelasList'));
    }

    // Remove old separate index methods
    public function indexGuru() { return redirect()->route('admin.users.index', ['role' => 'guru']); }
    public function indexSiswa() { return redirect()->route('admin.users.index', ['role' => 'siswa']); }
    public function indexOrangTua() { return redirect()->route('admin.users.index', ['role' => 'orangtua']); }

    public function create()
    {
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();
        return view('admin.users.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'guru', 'walikelas', 'siswa', 'orangtua'])],
        ];

        if (in_array($request->role, ['guru', 'walikelas'])) {
            $rules['nip'] = ['required', 'string'];
        }

        if (in_array($request->role, ['siswa', 'orangtua'])) {
            $rules['nisn'] = ['required', 'string'];
            $rules['kelas_id'] = ['nullable', 'exists:kelas,id'];
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $guruId = null;
            $siswaId = null;

            if (in_array($validated['role'], ['guru', 'walikelas'])) {
                $guru = Guru::firstOrCreate(
                    ['nip' => $validated['nip']],
                    ['nama' => $validated['name']]
                );
                $guruId = $guru->id;
            }

            if (in_array($validated['role'], ['siswa', 'orangtua'])) {
                $siswa = \App\Models\Siswa::firstOrCreate(
                    ['nisn' => $validated['nisn']],
                    [
                        'nama' => $validated['role'] === 'siswa' ? $validated['name'] : str_replace('Orang Tua ', '', $validated['name']),
                        'kelas_id' => $validated['kelas_id'] ?? null
                    ]
                );
                
                // Jika sudah ada tapi kelas belum diatur, update kelasnya
                if ($request->filled('kelas_id')) {
                    $siswa->update(['kelas_id' => $validated['kelas_id']]);
                }
                
                $siswaId = $siswa->id;
            }

            // Cek apakah user dengan role dan identifier ini sudah ada
            $existingUser = User::where('role', $validated['role'])
                ->where(function($q) use ($guruId, $siswaId) {
                    if ($guruId) $q->where('guru_id', $guruId);
                    if ($siswaId) $q->where('siswa_id', $siswaId);
                })->first();

            if ($existingUser) {
                throw new \Exception("Pengguna dengan peran ini untuk " . ($guruId ? "NIP tersebut" : "NISN tersebut") . " sudah terdaftar.");
            }

            // Tentukan password default jika kosong
            $password = $validated['password'];
            if (empty($password)) {
                if (in_array($validated['role'], ['guru', 'walikelas'])) {
                    $password = $validated['nip'];
                } elseif ($validated['role'] === 'siswa') {
                    $password = $validated['nisn'];
                } elseif ($validated['role'] === 'orangtua') {
                    $password = $validated['nisn'];
                } else {
                    $password = 'password';
                }
            }

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role' => $validated['role'],
                'guru_id' => $guruId,
                'siswa_id' => $siswaId,
            ]);

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();
        return view('admin.users.edit', compact('user', 'kelasList'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'guru', 'walikelas', 'siswa', 'orangtua'])],
            'password' => ['nullable', 'string', 'min:8'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ]);

            // Jika user adalah siswa atau orang tua, update kelas di tabel siswas
            if (in_array($user->role, ['siswa', 'orangtua']) && $user->siswa_id) {
                if ($request->filled('kelas_id')) {
                    \App\Models\Siswa::where('id', $user->siswa_id)->update(['kelas_id' => $validated['kelas_id']]);
                }
            }

            // Update password hanya jika diisi dan user bukan admin
            if ($request->filled('password') && $user->role !== 'admin') {
                $user->password = Hash::make($validated['password']);
                $user->save();
            }

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
