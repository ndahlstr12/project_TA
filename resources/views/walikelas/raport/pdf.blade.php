<!DOCTYPE html>
<html>
<head>
    <title>Raport {{ $siswa->nama }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .info-table { width: 100%; margin-bottom: 20px; font-size: 12px; }
        .main-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
        .main-table th, .main-table td { border: 1px solid #333; padding: 8px; text-align: left; }
        .main-table th { bg-color: #f2f2f2; }
        .section-title { font-weight: bold; margin-top: 20px; margin-bottom: 10px; text-decoration: underline; font-size: 14px; }
        .footer { margin-top: 50px; }
        .signature-box { float: right; width: 200px; text-align: center; }
        .signature-img { width: 100px; height: 100px; object-fit: contain; margin: 10px 0; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Hasil Belajar (E-Raport)</h2>
        <p>SMKN 1 Sungailiat</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">Nama Siswa</td><td width="35%">: {{ $siswa->nama }}</td>
            <td width="15%">Kelas</td><td width="35%">: {{ $siswa->kelas->nama_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td>NISN</td><td>: {{ $siswa->nisn }}</td>
            <td>Semester</td><td>: {{ $semester }}</td>
        </tr>
        <tr>
            <td>Tahun Ajaran</td><td>: {{ $tahunAjaran }}</td>
            <td>Wali Kelas</td><td>: {{ Auth::user()->name }}</td>
        </tr>
    </table>

    <div class="section-title">A. Capaian Pengetahuan & Keterampilan</div>
    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Mata Pelajaran</th>
                <th width="15%">Nilai</th>
                <th width="35%">Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilais as $index => $nilai)
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td>{{ $nilai->mapel->nama_mapel ?? '-' }}</td>
                <td align="center">{{ $nilai->nilai_angka }}</td>
                <td align="center">
                    @if($nilai->nilai_angka >= 90) A
                    @elseif($nilai->nilai_angka >= 80) B
                    @elseif($nilai->nilai_angka >= 70) C
                    @else D
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">B. Kehadiran</div>
    <table class="main-table" style="width: 50%;">
        <tr><td>Sakit</td><td align="center">{{ $raport->sakit ?? 0 }} hari</td></tr>
        <tr><td>Izin</td><td align="center">{{ $raport->izin ?? 0 }} hari</td></tr>
        <tr><td>Alpa</td><td align="center">{{ $raport->alpa ?? 0 }} hari</td></tr>
    </table>

    <div class="section-title">C. Catatan Wali Kelas</div>
    <div style="border: 1px solid #333; padding: 10px; font-size: 12px; min-height: 50px;">
        {{ $raport->catatan_wali ?? 'Siswa menunjukkan semangat belajar yang baik. Pertahankan prestasimu.' }}
    </div>

    <div class="footer">
        <div class="signature-box">
            <p>Sungailiat, {{ date('d F Y') }}</p>
            <p>Wali Kelas,</p>
            @if(Auth::user()->guru->ttd_digital)
                <img src="{{ public_path('storage/' . Auth::user()->guru->ttd_digital) }}" class="signature-img">
            @else
                <div style="height: 100px;"></div>
            @endif
            <p><strong>{{ Auth::user()->name }}</strong></p>
            <p>NIP. {{ Auth::user()->guru->nip ?? '-' }}</p>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>
