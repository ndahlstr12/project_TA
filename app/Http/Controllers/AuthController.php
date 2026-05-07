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

        // 1. Unified Login via Username (stores NISN/NIP/Email)
        if (Auth::attempt(['username' => $loginInput, 'password' => $password], $remember)) {
            return $this->authenticated($request, Auth::user());
        }

        // 2. Admin Login via Email
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $loginInput, 'password' => $password], $remember)) {
                return $this->authenticated($request, Auth::user());
            }
        }

        // 3. School Pattern: Guru (NIP)
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
        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        // Fallback search by NIP/NISN if username not found
        if (!$user) {
            $guru = Guru::where('nip', $request->username)->first();
            if ($guru) $user = User::where('guru_id', $guru->id)->first();
        }
        if (!$user) {
            $siswa = Siswa::where('nisn', $request->username)->first();
            if ($siswa) $user = User::where('siswa_id', $siswa->id)->first();
        }

        if ($user) {
            PasswordResetRequest::updateOrCreate(
                ['user_id' => $user->id, 'status' => 'pending']
            );
            return back()->with('success', 'Permintaan reset telah dikirim ke Admin.');
        }

        return back()->withErrors(['username' => 'ID Pengguna tidak ditemukan.']);
    }
}
