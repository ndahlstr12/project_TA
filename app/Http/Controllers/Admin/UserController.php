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
        ]);

        $user->update($validated);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
