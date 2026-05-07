<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Arial', sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 15px; }
        .header { text-align: center; border-bottom: 2px solid #6366f1; padding-bottom: 20px; margin-bottom: 20px; }
        .footer { text-align: center; font-size: 12px; color: #94a3b8; margin-top: 30px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #6366f1; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #4338ca;">E-Raport SMKN 1 Sungailiat</h2>
        </div>
        
        <p>Halo Bapak/Ibu Wali Murid dari <strong>{{ $siswa->nama }}</strong>,</p>
        
        <p>Terlampir kami sampaikan hasil evaluasi belajar (Raport) putra/putri Anda untuk Semester Ganjil Tahun Ajaran 2025/2026.</p>
        
        <p>Silakan unduh file PDF yang terlampir pada email ini untuk melihat rincian nilai dan perkembangan akademik.</p>
        
        <p style="margin-top: 30px;">
            Hormat kami,<br>
            <strong>Wali Kelas</strong><br>
            SMKN 1 Sungailiat
        </p>

        <div class="footer">
            &copy; 2026 TIM IT SMKN 1 Sungailiat. Email ini dikirim secara otomatis oleh sistem.
        </div>
    </div>
</body>
</html>
