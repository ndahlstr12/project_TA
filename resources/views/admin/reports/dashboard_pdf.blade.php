<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Dashboard Admin</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
        .section-title {
            background-color: #f4f4f4;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
            border-left: 4px solid #1a2c5b;
        }
        .stats-grid {
            width: 100%;
            margin-bottom: 25px;
        }
        .stats-box {
            width: 23%;
            float: left;
            background: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            margin-right: 1.5%;
        }
        .stats-box:last-child {
            margin-right: 0;
        }
        .stats-label {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .stats-value {
            font-size: 18px;
            font-weight: bold;
            color: #1a2c5b;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        table th {
            background-color: #1a2c5b;
            color: white;
            padding: 10px;
            text-align: left;
            text-transform: uppercase;
        }
        table td {
            border-bottom: 1px solid #eee;
            padding: 10px;
        }
        .status-selesai {
            color: #10b981;
            font-weight: bold;
        }
        .status-pending {
            color: #ef4444;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: right;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        .progress-bar-wrap {
            width: 100%;
            background: #eee;
            height: 10px;
            border-radius: 5px;
            margin: 5px 0;
        }
        .progress-bar {
            background: #1a2c5b;
            height: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SMKN 1 SUNGAILIAT</h1>
        <p>Laporan Ringkasan Dashboard Akademik - Sistem E-Raport</p>
        <p>Tanggal Cetak: {{ date('d F Y H:i') }}</p>
    </div>

    <div class="section-title">Statistik Utama</div>
    <div class="stats-grid clearfix">
        <div class="stats-box">
            <div class="stats-label">Total Siswa</div>
            <div class="stats-value">{{ $stats['total_siswa'] }}</div>
        </div>
        <div class="stats-box">
            <div class="stats-label">Rata-rata Nilai</div>
            <div class="stats-value">{{ $stats['rata_rata_nilai'] }}</div>
        </div>
        <div class="stats-box">
            <div class="stats-label">Kehadiran</div>
            <div class="stats-value">{{ $stats['kehadiran_rata'] }}</div>
        </div>
        <div class="stats-box">
            <div class="stats-label">Kriteria SPK</div>
            <div class="stats-value">{{ $stats['total_kriteria'] }}</div>
        </div>
    </div>

    <div class="section-title">Progres Penginputan Data</div>
    <div style="margin-bottom: 25px;">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="width: 50%; border: none; padding-left: 0;">
                    <strong>Wali Kelas (Input Raport)</strong><br>
                    <span style="font-size: 14px;">{{ $stats['walikelas_selesai'] }} / {{ $stats['walikelas_total'] }}</span>
                    @php $waliPerc = $stats['walikelas_total'] > 0 ? ($stats['walikelas_selesai'] / $stats['walikelas_total']) * 100 : 0; @endphp
                    <div class="progress-bar-wrap">
                        <div class="progress-bar" style="width: {{ $waliPerc }}%; background-color: #3b82f6;"></div>
                    </div>
                    <span style="font-size: 10px;">{{ round($waliPerc) }}% Selesai</span>
                </td>
                <td style="width: 50%; border: none; padding-right: 0;">
                    <strong>Guru Mapel (Upload Nilai)</strong><br>
                    <span style="font-size: 14px;">{{ $stats['guru_mapel_selesai'] }} / {{ $stats['guru_mapel_total'] }}</span>
                    @php $guruPerc = $stats['guru_mapel_total'] > 0 ? ($stats['guru_mapel_selesai'] / $stats['guru_mapel_total']) * 100 : 0; @endphp
                    <div class="progress-bar-wrap">
                        <div class="progress-bar" style="width: {{ $guruPerc }}%; background-color: #e8a020;"></div>
                    </div>
                    <span style="font-size: 10px;">{{ round($guruPerc) }}% Selesai</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Detail Status Tenaga Pendidik</div>
    <table>
        <thead>
            <tr>
                <th>Nama Tenaga Pendidik</th>
                <th>Peran</th>
                <th>Jenis Tugas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allTeachers as $teacher)
            <tr>
                <td>{{ $teacher->name }}</td>
                <td>{{ strtoupper($teacher->role) }}</td>
                <td>{{ $teacher->tipe_tugas }}</td>
                <td class="{{ $teacher->status_tugas === 'Selesai' ? 'status-selesai' : 'status-pending' }}">
                    {{ strtoupper($teacher->status_tugas) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dihasilkan secara otomatis oleh Sistem E-Raport SMKN 1 Sungailiat &bull; {{ date('Y') }}
    </div>
</body>
</html>
