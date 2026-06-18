<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Raport_{{ $siswa->nama }}</title>
    <style>
        @page { 
            margin: 0.8cm 1.2cm; 
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 9pt; 
            line-height: 1.4;
            color: #000;
        }
        .running-header {
            font-size: 7pt;
            border-bottom: 1px solid #000;
            margin-bottom: 15px;
            padding-bottom: 2px;
            display: table;
            width: 100%;
        }
        .running-header div {
            display: table-cell;
        }
        .running-header .left { text-align: left; width: 40%; }
        .running-header .center { text-align: center; width: 20%; }
        .running-header .right { text-align: right; width: 40%; }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            vertical-align: top;
            padding: 1px 0;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 5px 8px;
            vertical-align: top;
        }
        .main-table th {
            text-align: center;
            font-weight: bold;
        }
        .group-header {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            text-align: center;
            background-color: #eee;
            border: 1px solid #000;
            padding: 3px;
        }

        .attendance-box {
            width: 45%;
            float: left;
        }
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }
        .attendance-table td {
            border: 1px solid #000;
            padding: 4px 8px;
        }

        .notes-box-container {
            width: 52%;
            float: right;
        }
        .notes-header {
            text-align: center;
            font-weight: bold;
            border: 1px solid #000;
            border-bottom: none;
            padding: 3px;
        }
        .notes-content {
            border: 1px solid #000;
            padding: 10px;
            min-height: 80px;
            font-size: 8.5pt;
        }

        .full-box {
            border: 1px solid #000;
            padding: 10px;
            min-height: 40px;
            margin-bottom: 15px;
            font-size: 8.5pt;
        }

        .footer {
            margin-top: 30px;
            width: 100%;
            clear: both;
        }
        .footer table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer td {
            text-align: center;
            width: 33%;
            vertical-align: top;
        }
        .signature-space {
            height: 60px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .signature-img {
            max-height: 60px;
            max-width: 130px;
        }
        .clear { clear: both; }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- PAGE 1 & 2 -->
    <div class="running-header">
        <div class="left">{{ strtoupper(str_replace(' ', '-', $siswa->nama)) }}-{{ strtoupper(str_replace(' ', '-', $siswa->kelas->nama_kelas ?? '')) }}</div>
        <div class="center">1</div>
        <div class="right">Dicetak dari e-Rapor SMK v.8.0.2</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="18%">Nama Peserta Didik</td><td width="2%">:</td><td width="40%">{{ strtoupper($siswa->nama) }}</td>
            <td width="15%">Kelas</td><td width="2%">:</td><td width="23%">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nomor Induk/NISN</td><td>:</td><td>{{ $siswa->nis ?? '-' }} / {{ $siswa->nisn }}</td>
            <td>Fase</td><td>:</td><td>E</td>
        </tr>
        <tr>
            <td>Sekolah</td><td>:</td><td>SMKN 1 SUNGAILIAT</td>
            <td>Semester</td><td>:</td><td>{{ $semester }}</td>
        </tr>
        <tr>
            <td>Alamat</td><td>:</td><td>JALAN PEMUDA SUNGAILIAT</td>
            <td>Tahun Pelajaran</td><td>:</td><td>{{ $tahunAjaran }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Mata Pelajaran</th>
                <th width="10%">Nilai Akhir</th>
                <th width="50%">Capaian Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @if($nilaiUmum->count() > 0)
            <tr class="group-header">
                <td colspan="4">Mata Pelajaran Umum</td>
            </tr>
            @foreach($nilaiUmum as $nilai)
            <tr>
                <td align="center">{{ $no++ }}</td>
                <td>{{ $nilai->mapel->nama_mapel ?? '-' }}</td>
                <td align="center">{{ number_format($nilai->nilai_angka, 0) }}</td>
                <td style="font-size: 8pt; text-align: justify;">
                    {{ $nilai->capaian_kompetensi ?? 'Menunjukkan penguasaan yang baik dalam seluruh kompetensi mata pelajaran ini.' }}
                </td>
            </tr>
            @endforeach
            @endif

            @if($nilaiKejuruan->count() > 0)
            <tr class="group-header">
                <td colspan="4">Mata Pelajaran Kejuruan</td>
            </tr>
            @foreach($nilaiKejuruan as $nilai)
            <tr>
                <td align="center">{{ $no++ }}</td>
                <td>{{ $nilai->mapel->nama_mapel ?? '-' }}</td>
                <td align="center">{{ number_format($nilai->nilai_angka, 0) }}</td>
                <td style="font-size: 8pt; text-align: justify;">
                    {{ $nilai->capaian_kompetensi ?? 'Menunjukkan penguasaan yang baik dalam praktik dan teori kejuruan.' }}
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- PAGE 3 -->
    <div class="running-header">
        <div class="left">{{ strtoupper(str_replace(' ', '-', $siswa->nama)) }}-{{ strtoupper(str_replace(' ', '-', $siswa->kelas->nama_kelas ?? '')) }}</div>
        <div class="center">3</div>
        <div class="right">Dicetak dari e-Rapor SMK v.8.0.2</div>
    </div>

    <table class="info-table" style="margin-bottom: 10px;">
        <tr>
            <td width="20%">Nama Peserta Didik</td><td width="2%">:</td><td width="78%">{{ strtoupper($siswa->nama) }}</td>
        </tr>
        <tr>
            <td>Nomor Induk/NISN</td><td>:</td><td>{{ $siswa->nis ?? '-' }} / {{ $siswa->nisn }}</td>
        </tr>
        <tr>
            <td>Kelas</td><td>:</td><td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tahun Pelajaran</td><td>:</td><td>{{ $tahunAjaran }}</td>
        </tr>
        <tr>
            <td>Semester</td><td>:</td><td>{{ $semester }}</td>
        </tr>
    </table>

    <div class="section-title" style="background-color: transparent;">Kokurikuler</div>
    <div class="full-box" style="min-height: 80px;">
        {{ $raport->kokurikuler ?? 'Siswa telah mengikuti rangkaian proyek penguatan profil pelajar pancasila dengan baik sesuai dengan kurikulum merdeka.' }}
    </div>

    <table class="main-table" style="margin-bottom: 20px;">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Ekstrakurikuler</th>
                <th width="60%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ekstrakurikulers as $index => $ekstra)
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td>{{ $ekstra->nama_ekstra }}</td>
                <td>{{ $ekstra->keterangan }}</td>
            </tr>
            @empty
            <tr>
                <td align="center">1</td>
                <td>-</td>
                <td>-</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="width: 100%; margin-bottom: 20px;">
        <div class="attendance-box">
            <table class="attendance-table">
                <tr>
                    <td width="60%">Sakit</td>
                    <td width="40%">: {{ $raport->sakit ?? 0 }} hari</td>
                </tr>
                <tr>
                    <td>Izin</td>
                    <td>: {{ $raport->izin ?? 0 }} hari</td>
                </tr>
                <tr>
                    <td>Tanpa Keterangan</td>
                    <td>: {{ $raport->alpa ?? 0 }} hari</td>
                </tr>
            </table>
        </div>
        <div class="notes-box-container">
            <div class="notes-header">Catatan Wali Kelas</div>
            <div class="notes-content">
                {{ $raport->catatan_wali ?? 'Ananda ' . $siswa->nama . ' telah mengikuti kegiatan pembelajaran dengan baik. Perlu meningkatkan motivasi belajar dan kedisiplinan agar hasil yang dicapai lebih optimal.' }}
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="section-title" style="background-color: transparent;">Tanggapan Orang Tua/Wali Murid</div>
    <div class="full-box" style="min-height: 60px;"></div>

    <div class="footer">
        <table style="width: 100%;">
            <tr>
                <td width="33%">
                    <br>
                    Orang Tua/Wali
                    <div class="signature-space"></div>
                    ......................................................
                </td>
                <td width="33%">
                    <br>
                    <br>
                    Mengetahui,<br>
                    Kepala Sekolah
                    <div class="signature-space" style="height: 40px;"></div>
                    <strong>NINA ERLINA, M.Pd</strong><br>
                    NIP. 197604252000122002
                </td>
                <td width="33%">
                    Bangka, {{ date('d Desember Y') }}<br>
                    Wali Kelas
                    <div class="signature-space">
                        @if(isset($raport->wali) && $raport->wali->ttd_digital)
                            <img src="{{ public_path('storage/' . $raport->wali->ttd_digital) }}" class="signature-img">
                        @endif
                    </div>
                    <strong>{{ $raport->wali->nama ?? 'ASFIATUL BADIAH, S.Pd' }}</strong><br>
                    NIP. {{ $raport->wali->nip ?? '197905232005012007' }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
