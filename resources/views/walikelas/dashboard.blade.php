@extends('layouts.admin')

@section('title', 'Dashboard Wali Kelas')
@section('page_title', 'Overview Wali Kelas')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-lg shadow-blue-500/20">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-blue-100 mt-2 font-medium">Anda adalah wali kelas dari <span class="bg-white/20 px-2 py-0.5 rounded text-white font-bold">{{ $kelas->nama_kelas ?? 'Belum Ditentukan' }}</span></p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('walikelas.raport.index') }}" class="px-6 py-3 bg-white text-blue-600 rounded-2xl font-bold text-sm hover:bg-blue-50 transition-all shadow-sm flex items-center gap-2">
                    <i class="ti ti-file-certificate"></i>
                    Manajemen Raport
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card-pro p-6 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                <i class="ti ti-users text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Siswa</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ count($siswas) }}</p>
            </div>
        </div>

        <div class="card-pro p-6 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                <i class="ti ti-calendar-event text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jadwal Hari Ini</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $jadwals->where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->count() }} Sesi</p>
            </div>
        </div>

        <div class="card-pro p-6 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                <i class="ti ti-notebook text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jurnal Perilaku</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">Aktif</p>
            </div>
        </div>

        <div class="card-pro p-6 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-500">
                <i class="ti ti-award text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Peringkat SPK</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">Tersedia</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Jadwal Mengajar -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-black uppercase tracking-widest text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="ti ti-calendar text-blue-500"></i> Jadwal Mengajar Saya
                </h3>
            </div>
            <div class="card-pro overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-100 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                <th class="px-6 py-4">Hari</th>
                                <th class="px-6 py-4">Jam</th>
                                <th class="px-6 py-4">Mata Pelajaran</th>
                                <th class="px-6 py-4">Kelas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                            @forelse($jadwals as $jadwal)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase tracking-tighter">{{ $jadwal->hari }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700 dark:text-slate-300">
                                    {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-900 dark:text-white">
                                    {{ $jadwal->mapel->nama_mapel ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500">
                                    {{ $jadwal->kelas->nama_kelas ?? '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-400 italic text-xs font-medium uppercase tracking-widest">Belum ada jadwal mengajar</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Info Kelas Wali -->
        <div class="space-y-6">
            <h3 class="text-sm font-black uppercase tracking-widest text-slate-900 dark:text-white flex items-center gap-2">
                <i class="ti ti-id text-indigo-500"></i> Informasi Kelas Wali
            </h3>
            <div class="card-pro p-6 space-y-6">
                <div class="flex flex-col items-center text-center pb-6 border-b border-slate-100 dark:border-white/5">
                    <div class="w-20 h-20 rounded-3xl bg-indigo-500/10 flex items-center justify-center text-indigo-500 mb-4">
                        <i class="ti ti-school text-4xl"></i>
                    </div>
                    <h4 class="text-lg font-extrabold text-slate-900 dark:text-white">{{ $kelas->nama_kelas ?? 'Belum Ditentukan' }}</h4>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $kelas->jurusan ?? 'Semua Jurusan' }}</p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-400 uppercase tracking-tighter">Tahun Ajaran</span>
                        <span class="font-black text-slate-900 dark:text-white">2026/2027</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-400 uppercase tracking-tighter">Tingkat</span>
                        <span class="font-black text-slate-900 dark:text-white">{{ $kelas->tingkat ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-400 uppercase tracking-tighter">Semester</span>
                        <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-600 font-black">GENAP</span>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="{{ route('walikelas.jurnal.index') }}" class="w-full flex items-center justify-center gap-2 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl text-xs font-bold hover:opacity-90 transition-all uppercase tracking-widest">
                        <i class="ti ti-notebook"></i> Input Jurnal Perilaku
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
