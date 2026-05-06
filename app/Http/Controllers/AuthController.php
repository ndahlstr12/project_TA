<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $rules = [
            'role' => ['required', 'in:siswa,guru'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if ($request->role === 'siswa') {
            $rules['id_number'] = ['required', 'string', 'min:10']; // NISN
        } else {
            $rules['id_number'] = ['required', 'string', 'min:8']; // NIP
        }

        $validated = $request->validate($rules);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $siswaId = null;
            $guruId = null;

            if ($validated['role'] === 'siswa') {
                // Logika Siswa (Cari/Buat Profil)
                $siswa = \App\Models\Siswa::where('nisn', $validated['id_number'])->first();
                if (!$siswa) {
                    $siswa = \App\Models\Siswa::create([
                        'nisn' => $validated['id_number'],
                        'nama' => $validated['name'],
                        'kelas' => 'Belum Ditentukan',
                        'jenis_kelamin' => 'L',
                    ]);
                }
                $siswaId = $siswa->id;
                
                // Cek apakah sudah ada akun
                if (\App\Models\User::where('siswa_id', $siswaId)->where('role', 'siswa')->exists()) {
                    return back()->withErrors(['id_number' => 'Akun dengan NISN ini sudah terdaftar.'])->withInput();
                }
            } else {
                // Logika Guru (NIP WAJIB sudah didaftarkan Admin di Master Data)
                $guru = \App\Models\Guru::where('nip', $validated['id_number'])->first();
                if (!$guru) {
                    return back()->withErrors(['id_number' => 'NIP Anda belum terdaftar di sistem. Silakan hubungi Admin.'])->withInput();
                }
                $guruId = $guru->id;

                // Cek apakah sudah ada akun
                if (\App\Models\User::where('guru_id', $guruId)->exists()) {
                    return back()->withErrors(['id_number' => 'Akun dengan NIP ini sudah terdaftar.'])->withInput();
                }
            }

            // Buat akun User
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => null,
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
                'role' => $validated['role'],
                'siswa_id' => $siswaId,
                'guru_id' => $guruId,
            ]);

            \Illuminate\Support\Facades\DB::commit();
            \Illuminate\Support\Facades\Auth::login($user);

            // Redirect sesuai role
            if ($user->role === 'siswa') {
                return redirect()->route('siswa.dashboard')->with('success', 'Selamat datang!');
            }
            return redirect()->route('guru.dashboard')->with('success', 'Selamat datang, Bapak/Ibu Guru.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withErrors(['id_number' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function login(Request $request)
    {
        $loginInput = $request->input('login'); 
        $password = $request->input('password');

        // 1. Coba Login sebagai Admin (via Email)
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $loginInput, 'password' => $password])) {
                return $this->authenticated($request, Auth::user());
            }
        }

        // 2. Coba Login sebagai Guru (via NIP)
        // Kita cari di tabel gurus dulu berdasarkan NIP
        $guru = \App\Models\Guru::where('nip', $loginInput)->first();
        if ($guru) {
            // Jika data guru ada, cari user yang terhubung ke guru_id tersebut
            $userGuru = \App\Models\User::where('guru_id', $guru->id)->first();
            
            // Jika user ditemukan, cek passwordnya secara manual
            if ($userGuru && \Illuminate\Support\Facades\Hash::check($password, $userGuru->password)) {
                Auth::login($userGuru);
                return $this->authenticated($request, $userGuru);
            }
        }

        // 3. Coba Login sebagai Siswa / Orang Tua (via NISN)
        $siswa = \App\Models\Siswa::where('nisn', $loginInput)->first();
        if ($siswa) {
            // Cari semua user yang terhubung ke siswa ini (bisa Siswa itu sendiri atau Orang Tuanya)
            $users = \App\Models\User::where('siswa_id', $siswa->id)->get();
            
            foreach ($users as $u) {
                if (\Illuminate\Support\Facades\Hash::check($password, $u->password)) {
                    Auth::login($u);
                    return $this->authenticated($request, $u);
                }
            }
        }

        return back()->withErrors([
            'login' => 'Login gagal. Silakan periksa kembali ID (NIP/NISN) dan Password Anda.',
        ])->onlyInput('login');
    }

    protected function authenticated(Request $request, $user)
    {
        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->intended('admin/dashboard');
        } elseif ($user->role === 'guru') {
            return redirect()->intended('teacher/dashboard');
        } elseif ($user->role === 'walikelas') {
            return redirect()->intended('class-teacher/dashboard');
        } elseif ($user->role === 'siswa') {
            return redirect()->intended('student/dashboard');
        } elseif ($user->role === 'orangtua') {
            return redirect()->intended('parent/dashboard');
        }

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
