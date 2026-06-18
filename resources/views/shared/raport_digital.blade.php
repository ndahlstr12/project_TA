@extends('layouts.admin')

@section('title', 'Raport Digital')
@section('page_title', 'Raport Digital - ' . $siswa->nama)

@section('content')
<div class="space-y-8">
    
    <div class="flex flex-col md:flex-row justify-between items-start gap-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-3xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                <i class="ti ti-file-certificate text-3xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $siswa->nama }}</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
                    Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }} | TA: {{ $tahunAjaran }} | Semester: {{ $semester }}
                </p>
            </div>
        </div>
        <div class="flex gap-3">
            @php
                $role = Auth::user()->role;
                $exportRoute = '#';
                if ($role === 'walikelas') {
                    $exportRoute = route('walikelas.raport.export-pdf', $siswa->id);
                } elseif ($role === 'orangtua') {
                    $exportRoute = route('parent.raport.export-pdf', $raport->id);
                }
            @endphp
            
            @if($role !== 'siswa')
            <a href="{{ $exportRoute }}" class="px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all flex items-center gap-2">
                <i class="ti ti-printer"></i> Cetak PDF
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <!-- Kehadiran Summary -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="card-pro p-6 bg-white dark:bg-slate-900 border-b-4 border-emerald-500">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Sakit</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $raport->sakit ?? 0 }} <span class="text-xs font-bold text-slate-400 uppercase">Hari</span></p>
                </div>
                <div class="card-pro p-6 bg-white dark:bg-slate-900 border-b-4 border-amber-500">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Izin</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $raport->izin ?? 0 }} <span class="text-xs font-bold text-slate-400 uppercase">Hari</span></p>
                </div>
                <div class="card-pro p-6 bg-white dark:bg-slate-900 border-b-4 border-rose-500">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Alpa</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $raport->alpa ?? 0 }} <span class="text-xs font-bold text-slate-400 uppercase">Hari</span></p>
                </div>
            </div>

            <!-- Grades Table -->
            <div class="card-pro overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                    <h4 class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white">Capaian Hasil Belajar</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-100 dark:border-white/5 text-[9px] font-black text-slate-500 uppercase tracking-widest">
                                <th class="px-6 py-4 w-16 text-center">No</th>
                                <th class="px-6 py-4">Mata Pelajaran</th>
                                <th class="px-6 py-4 text-center">Nilai</th>
                                <th class="px-6 py-4">Capaian Kompetensi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                            @php $no = 1; @endphp
                            
                            @if($nilaiUmum->count() > 0)
                            <tr class="bg-indigo-500/5">
                                <td colspan="4" class="px-6 py-2 text-[9px] font-black text-indigo-500 uppercase tracking-widest">A. Kelompok Umum</td>
                            </tr>
                            @foreach($nilaiUmum as $nilai)
                            <tr class="text-sm">
                                <td class="px-6 py-4 text-center font-bold text-slate-400 text-xs">{{ $no++ }}</td>
                                <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300">{{ $nilai->mapel->nama_mapel ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-lg @if($nilai->nilai_angka < 75) bg-rose-500/10 text-rose-500 @else bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 @endif font-black">
                                        {{ number_format($nilai->nilai_angka, 0) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 leading-relaxed italic">
                                    {{ $nilai->capaian_kompetensi ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                            @endif

                            @if($nilaiKejuruan->count() > 0)
                            <tr class="bg-purple-500/5">
                                <td colspan="4" class="px-6 py-2 text-[9px] font-black text-purple-500 uppercase tracking-widest">B. Kelompok Kejuruan</td>
                            </tr>
                            @foreach($nilaiKejuruan as $nilai)
                            <tr class="text-sm">
                                <td class="px-6 py-4 text-center font-bold text-slate-400 text-xs">{{ $no++ }}</td>
                                <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300">{{ $nilai->mapel->nama_mapel ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-lg @if($nilai->nilai_angka < 75) bg-rose-500/10 text-rose-500 @else bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 @endif font-black">
                                        {{ number_format($nilai->nilai_angka, 0) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 leading-relaxed italic">
                                    {{ $nilai->capaian_kompetensi ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kokurikuler -->
            @if($raport && $raport->kokurikuler)
            <div class="card-pro p-8 bg-amber-500/5 border-l-4 border-amber-500">
                <h4 class="text-xs font-black uppercase tracking-widest text-amber-600 mb-4 flex items-center gap-2">
                    <i class="ti ti-bulb"></i> Proyek Penguatan Profil Pelajar Pancasila
                </h4>
                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed font-medium">{{ $raport->kokurikuler }}</p>
            </div>
            @endif

            <!-- Ekstrakurikuler -->
            @if($ekstrakurikulers->count() > 0)
            <div class="card-pro p-8">
                <h4 class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white mb-6">Ekstrakurikuler</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($ekstrakurikulers as $ekstra)
                    <div class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                        <p class="text-sm font-black text-slate-800 dark:text-white">{{ $ekstra->nama_ekstra }}</p>
                        <p class="text-xs text-slate-500 font-medium mt-1">{{ $ekstra->keterangan }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-8">
            <!-- Catatan Wali Kelas -->
            <div class="card-pro p-8 bg-indigo-600 text-white">
                <h4 class="text-xs font-black uppercase tracking-widest text-indigo-200 mb-6">Catatan Wali Kelas</h4>
                <p class="text-sm font-medium leading-relaxed italic">"{{ $raport->catatan_wali ?? 'Belum ada catatan.' }}"</p>
            </div>

            <!-- AI Saran -->
            @if($raport && $raport->saran_ai)
            <div class="card-pro p-8 border-2 border-dashed border-blue-500/30 bg-blue-500/5">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <i class="ti ti-robot text-xl"></i>
                    </div>
                    <h4 class="text-xs font-black uppercase tracking-widest text-blue-600">Saran Akademik (AI)</h4>
                </div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300 leading-relaxed italic">"{{ $raport->saran_ai }}"</p>
            </div>
            @endif

            <!-- Info Panel -->
            <div class="p-6 bg-slate-50 dark:bg-white/5 rounded-3xl border border-slate-100 dark:border-white/5">
                <h5 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Masa Berlaku</h5>
                <p class="text-xs font-bold text-slate-700 dark:text-slate-300">Data ini adalah salinan digital resmi. Untuk keperluan administrasi formal, silakan hubungi sekolah guna mendapatkan raport fisik bertanda tangan basah.</p>
            </div>
        </div>
    </div>

</div>
@endsection
