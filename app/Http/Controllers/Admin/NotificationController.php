<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $resetRequests = \App\Models\PasswordResetRequest::where('status', 'pending')->with('user')->get();

        return view('admin.notifications.index', compact('resetRequests'));
    }

    public function resolvePasswordReset($id)
    {
        $request = \App\Models\PasswordResetRequest::findOrFail($id);
        $user = $request->user;
        
        // Reset password menjadi username (NISN/NIP) sesuai permintaan
        // Jika username kosong (misal admin), gunakan email atau default password123
        $newPassword = $user->username ?? ($user->email ?? 'password123');
        
        $user->update(['password' => \Illuminate\Support\Facades\Hash::make($newPassword)]);
        
        $request->update(['status' => 'resolved']);

        return back()->with('success', 'Password user ' . $user->name . ' telah direset menjadi: ' . $newPassword);
    }

    public function update(Request $request)
    {
        return back()->with('success', 'Konfigurasi notifikasi berhasil diperbarui.');
    }
}
