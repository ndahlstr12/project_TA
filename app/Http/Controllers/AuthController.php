<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\PasswordResetRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $loginInput = $request->input('login'); 
        $password = $request->input('password');
        $remember = $request->has('remember');

        // 1. Cek di tabel User (Admin, Guru, Siswa yang sudah punya akun)
        // Mengecek apakah input cocok dengan email ATAU username
        $user = User::where('email', $loginInput)
                    ->orWhere('username', $loginInput)
                    ->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $remember);
            return $this->authenticated($request, $user);
        }

        // 2. Pattern Sekolah: Guru (NIP) - Jika belum login via langkah 1
        $guru = Guru::where('nip', $loginInput)->first();
        if ($guru) {
            $user = User::where('guru_id', $guru->id)->first();
            if ($user && Hash::check($password, $user->password)) {
                Auth::login($user, $remember);
                return $this->authenticated($request, $user);
            }
        }

        // 4. School Pattern: Siswa (NISN) & Orang Tua (NISN.ortu)
        $isOrtu = str_ends_with($loginInput, '.ortu');
        $searchId = $isOrtu ? explode('.', $loginInput)[0] : $loginInput;

        $siswa = Siswa::where('nisn', $searchId)->first();
        if ($siswa) {
            $role = $isOrtu ? 'orangtua' : 'siswa';
            $user = User::where('siswa_id', $siswa->id)->where('role', $role)->first();
            
            if ($user && Hash::check($password, $user->password)) {
                Auth::login($user, $remember);
                return $this->authenticated($request, $user);
            }
        }

        return back()->withErrors([
            'login' => 'Kredensial yang Anda masukkan salah.',
        ])->onlyInput('login');
    }

    protected function authenticated(Request $request, $user)
    {
        $request->session()->regenerate();

        $routes = [
            'admin' => 'admin/dashboard',
            'guru' => 'teacher/dashboard',
            'walikelas' => 'class-teacher/dashboard',
            'siswa' => 'student/dashboard',
            'orangtua' => 'parent/dashboard',
        ];

        return redirect()->intended($routes[$user->role] ?? '/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['username' => 'required']);
        
        $loginInput = $request->username;

        // Cari user berdasarkan username, email, atau cek ke tabel guru/siswa
        $user = User::where('username', $loginInput)
            ->orWhere('email', $loginInput)
            ->first();

        // Fallback: Jika tidak ditemukan di tabel User, coba cari di tabel Guru/Siswa
        if (!$user) {
            $guru = Guru::where('nip', $loginInput)->first();
            if ($guru) $user = User::where('guru_id', $guru->id)->first();
        }
        if (!$user) {
            $siswa = Siswa::where('nisn', $loginInput)->first();
            if ($siswa) $user = User::where('siswa_id', $siswa->id)->first();
        }

        if ($user) {
            // Catat ke tabel riwayat reset dengan status 'pending' agar admin yang memproses
            PasswordResetRequest::updateOrCreate(
                ['user_id' => $user->id],
                ['status' => 'pending']
            );

            return back()->with('success', 'Permintaan reset kata sandi telah dikirim ke Admin. Silakan hubungi admin sekolah untuk memproses permintaan Anda.');
        }

        return back()->withErrors(['username' => 'ID Pengguna (NISN/NIP) tidak terdaftar di sistem.']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'role' => 'required|in:siswa,guru',
            'id_number' => 'required',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $role = $request->role;
        $idNumber = $request->id_number;
        $userData = [
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => $role,
            'username' => $idNumber,
        ];

        if ($role === 'siswa') {
            $siswa = Siswa::where('nisn', $idNumber)->first();
            if (!$siswa) {
                return back()->withErrors(['id_number' => 'NISN tidak terdaftar di sistem.'])->withInput();
            }
            if (User::where('siswa_id', $siswa->id)->where('role', 'siswa')->exists()) {
                return back()->withErrors(['id_number' => 'Akun untuk NISN ini sudah terdaftar.'])->withInput();
            }
            $userData['siswa_id'] = $siswa->id;
        } else {
            $guru = Guru::where('nip', $idNumber)->first();
            if (!$guru) {
                return back()->withErrors(['id_number' => 'NIP tidak terdaftar di sistem.'])->withInput();
            }
            if (User::where('guru_id', $guru->id)->exists()) {
                return back()->withErrors(['id_number' => 'Akun untuk NIP ini sudah terdaftar.'])->withInput();
            }
            $userData['guru_id'] = $guru->id;
        }

        User::create($userData);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan masuk.');
    }
}
