<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

use App\Services\AiRecommendationService;

class ProfileController extends Controller
{
    protected $aiService;

    public function __construct(AiRecommendationService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'email_orang_tua' => ['nullable', 'email', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
        ];

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            
            $path = $request->file('foto')->store('profile-photos', 'public');
            $data['foto'] = $path;
        }

        $user->update($data);

        // Jika user adalah guru atau walikelas, handle Tanda Tangan Digital
        if (($user->role === 'guru' || $user->role === 'walikelas') && $user->guru) {
            $request->validate([
                'ttd_digital' => ['nullable', 'image', 'mimes:png', 'max:1024'],
            ]);

            if ($request->hasFile('ttd_digital')) {
                // Validasi AI: Apakah ini benar-benar tanda tangan?
                $isSignature = $this->aiService->validateSignature($request->file('ttd_digital')->path());
                
                if (!$isSignature) {
                    return back()->with('error', 'Gagal memperbarui TTD: Sistem mendeteksi gambar yang Anda unggah bukan merupakan tanda tangan manual. Silakan unggah gambar tanda tangan yang jelas dengan format PNG.');
                }

                // Hapus TTD lama jika ada
                if ($user->guru->ttd_digital) {
                    Storage::disk('public')->delete($user->guru->ttd_digital);
                }
                
                $ttdPath = $request->file('ttd_digital')->store('ttd-digital', 'public');
                $user->guru->update(['ttd_digital' => $ttdPath]);
            }
        }

        // Jika user adalah siswa atau orang tua, update email ortu di tabel siswas
        if (($user->role === 'siswa' || $user->role === 'orangtua') && $user->siswa) {
            $user->siswa->update([
                'email_orang_tua' => $validated['email_orang_tua']
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        // Admin tidak diperbolehkan ganti password demi keamanan sistem
        if ($user->role === 'admin') {
            return back()->with('error', 'Akun Administrator tidak diperbolehkan mengganti kata sandi.');
        }

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user->password = $request->password;
        $user->must_change_password = false;
        $user->save();

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
