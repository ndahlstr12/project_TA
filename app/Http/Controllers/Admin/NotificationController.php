<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Simulasi data konfigurasi
        $config = [
            'mail_subject' => 'E-Raport Semester Ganjil 2025/2026 - SMKN 1 Sungailiat',
            'mail_body' => 'Halo Bapak/Ibu, Berikut kami lampirkan hasil raport putra/putri Anda...',
            'auto_send' => true,
        ];

        $resetRequests = \App\Models\PasswordResetRequest::where('status', 'pending')->with('user')->get();

        return view('admin.notifications.index', compact('config', 'resetRequests'));
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

    public function sendRaport($siswaId)
    {
        $siswa = \App\Models\Siswa::findOrFail($siswaId);

        if (!$siswa->email_orang_tua) {
            return back()->with('error', 'Email orang tua belum diatur untuk siswa ini.');
        }

        try {
            // Logika simulasi pembuatan PDF
            // Dalam implementasi nyata, gunakan DomPDF untuk generate file
            $pdfPath = storage_path('app/public/raports/raport_' . $siswa->nisn . '.pdf');
            
            // Pastikan folder ada
            if (!file_exists(dirname($pdfPath))) {
                mkdir(dirname($pdfPath), 0755, true);
            }

            // Contoh: Simulasikan file PDF (Hanya untuk testing)
            file_put_contents($pdfPath, "Ini adalah isi raport PDF simulasi untuk " . $siswa->nama);

            \Illuminate\Support\Facades\Mail::to($siswa->email_orang_tua)->send(new \App\Mail\RaportMail($siswa, $pdfPath));

            return back()->with('success', 'Raport berhasil dikirim ke email: ' . $siswa->email_orang_tua);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        // Logika simpan konfigurasi
        return back()->with('success', 'Konfigurasi notifikasi berhasil diperbarui.');
    }
}
