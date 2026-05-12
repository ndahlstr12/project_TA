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
        $handle = fopen($file->getRealPath(), 'r');
        
        // Lewati header
        fgetcsv($handle);

        $count = 0;
        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if (count($data) >= 5) {
                    $name = $data[0];
                    $email = $data[1];
                    $password = $data[2];
                    $role = $data[3];
                    $identifier = $data[4]; // NIP atau NISN

                    $guruId = null;
                    $siswaId = null;

                    if (in_array($role, ['guru', 'walikelas'])) {
                        $guru = Guru::firstOrCreate(
                            ['nip' => $identifier],
                            ['nama' => $name]
                        );
                        $guruId = $guru->id;
                    }

                    if (in_array($role, ['siswa', 'orangtua'])) {
                        $siswa = \App\Models\Siswa::where('nisn', $identifier)->first();
                        if ($siswa) {
                            $siswaId = $siswa->id;
                        }
                    }

                    User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'role' => $role,
                        'guru_id' => $guruId,
                        'siswa_id' => $siswaId,
                    ]);
                    $count++;
                }
            }
            DB::commit();
            fclose($handle);
            return back()->with('success', "$count pengguna berhasil diimport.");
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $role = $request->query('role');
        $query = User::query();

        if ($role) {
            if ($role === 'guru') {
                $query->whereIn('role', ['guru', 'walikelas']);
            } else {
                $query->where('role', $role);
            }
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $activeTab = $role ?: 'all';

        return view('admin.users.index', compact('users', 'activeTab'));
    }

    // Remove old separate index methods
    public function indexGuru() { return redirect()->route('admin.users.index', ['role' => 'guru']); }
    public function indexSiswa() { return redirect()->route('admin.users.index', ['role' => 'siswa']); }
    public function indexOrangTua() { return redirect()->route('admin.users.index', ['role' => 'orangtua']); }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'guru', 'walikelas', 'siswa', 'orangtua'])],
        ];

        if (in_array($request->role, ['guru', 'walikelas'])) {
            $rules['nip'] = ['required', 'string', 'unique:gurus,nip'];
        }

        if (in_array($request->role, ['siswa', 'orangtua'])) {
            $rules['nisn'] = ['required', 'string', 'exists:siswas,nisn'];
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $guruId = null;
            $siswaId = null;

            if (in_array($validated['role'], ['guru', 'walikelas'])) {
                $guru = Guru::create([
                    'nip' => $validated['nip'],
                    'nama' => $validated['name'],
                ]);
                $guruId = $guru->id;
            }

            if (in_array($validated['role'], ['siswa', 'orangtua'])) {
                $siswa = \App\Models\Siswa::where('nisn', $validated['nisn'])->first();
                $siswaId = $siswa->id;
            }

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
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
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'guru', 'walikelas', 'siswa', 'orangtua'])],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        // Update password hanya jika diisi dan user bukan admin
        if ($request->filled('password') && $user->role !== 'admin') {
            $user->password = $request->password;
            $user->save();
        }

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
