@extends('layouts.admin')

@section('title', 'Siswa Dashboard')
@section('page_title', 'Ringkasan Akademik')

@section('content')
<div class="space-y-12">
    
    <!-- Soft Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 bg-white/40 dark:bg-white/5 p-6 md:p-10 rounded-[2rem] md:rounded-[2.5rem] border border-white dark:border-white/5 backdrop-blur-md">
        <div class="flex items-center gap-4 md:gap-6">
            <div class="w-16 h-16 md:w-20 md:h-20 bg-rose-100 dark:bg-rose-600/20 rounded-2xl md:rounded-3xl flex items-center justify-center text-rose-600 shrink-0">
                <i data-lucide="sparkles" class="w-8 h-8 md:w-10 md:h-10"></i>
            </div>
            <div class="min-w-0">
                <h1 class="text-xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white truncate">Halo, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
                <p class="text-[10px] md:text-sm text-slate-400 font-medium mt-1 leading-relaxed">Hari ini ada {{ $jadwal->count() }} mata pelajaran untuk Anda.</p>
            </div>
        </div>
        <div class="flex gap-4 w-full lg:w-auto">
            <a href="{{ route('siswa.raport.index') }}" class="flex-1 lg:flex-none px-6 py-4 bg-white dark:bg-surface-800 text-slate-600 dark:text-slate-300 text-[10px] md:text-xs font-bold rounded-2xl shadow-sm border border-slate-100 dark:border-white/5 transition-all hover:shadow-md hover:scale-105 uppercase tracking-widest flex items-center justify-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                E-Raport
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 md:gap-8">
        <div class="card-soft p-6 md:p-8 group hover:scale-[1.02]">
            <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-rose-50 dark:bg-rose-600/10 flex items-center justify-center text-rose-600 mb-6 group-hover:bg-rose-600 group-hover:text-white transition-all duration-500 shrink-0">
                <i data-lucide="clock" class="w-6 h-6 md:w-7 md:h-7"></i>
            </div>
            <p class="text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Kehadiran</p>
            <h3 class="text-2xl md:text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ $persentaseHadir }}% <span class="text-xs font-bold text-slate-300 uppercase">Hadir</span></h3>
        </div>

        <div class="card-soft p-6 md:p-8 group hover:scale-[1.02]">
            <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-sky-50 dark:bg-sky-600/10 flex items-center justify-center text-sky-600 mb-6 group-hover:bg-sky-600 group-hover:text-white transition-all duration-500 shrink-0">
                <i data-lucide="clipboard-check" class="w-6 h-6 md:w-7 md:h-7"></i>
            </div>
            <p class="text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">CBT Aktif</p>
            <h3 class="text-2xl md:text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ $cbtAktif->count() }} <span class="text-xs font-bold text-slate-300 uppercase">Ujian</span></h3>
        </div>

        <div class="card-soft p-6 md:p-8 group hover:scale-[1.02]">
            <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-amber-50 dark:bg-amber-600/10 flex items-center justify-center text-amber-600 mb-6 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500 shrink-0">
                <i data-lucide="award" class="w-6 h-6 md:w-7 md:h-7"></i>
            </div>
            <p class="text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">Nilai Terbaru</p>
            <h3 class="text-2xl md:text-3xl font-black tracking-tight text-slate-900 dark:text-white truncate">{{ $latestGrades->first()->nilai_angka ?? 'N/A' }} <span class="text-xs font-bold text-slate-300 uppercase">{{ $latestGrades->first()->mapel->nama_mapel ?? '' }}</span></h3>
        </div>
    </div>

    <!-- Main Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 card-soft overflow-hidden">
            <div class="p-8 border-b border-slate-50 dark:border-white/5 flex items-center justify-between bg-white/50 dark:bg-white/5">
                <h3 class="text-sm font-bold uppercase tracking-widest text-slate-900 dark:text-white">Jadwal Pelajaran Hari Ini</h3>
                <i data-lucide="calendar-days" class="w-4 h-4 text-slate-300"></i>
            </div>
            <div class="p-0">
                <div class="divide-y divide-slate-50 dark:divide-white/5">
                    @forelse($jadwal as $j)
                    <div class="p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between hover:bg-slate-50/50 dark:hover:bg-white/5 transition-all group gap-4">
                        <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-10">
                            <span class="text-[10px] md:text-[11px] font-black text-slate-400 md:text-slate-200 group-hover:text-rose-500 transition-colors w-auto md:w-24 tracking-tighter uppercase">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</span>
                            <div class="min-w-0">
                                <p class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-200 break-words">{{ $j->mapel->nama_mapel }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 truncate">{{ $j->guru->nama }}</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="hidden md:block w-5 h-5 text-slate-200 group-hover:translate-x-1 transition-all"></i>
                    </div>
                    @empty
                    <div class="p-10 text-center">
                        <p class="text-sm text-slate-400">Tidak ada jadwal hari ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-10">
            @if($cbtAktif->count() > 0)
            <div class="card-soft p-10 bg-gradient-to-br from-rose-500 to-pink-600 text-white border-none relative overflow-hidden">
                <div class="absolute -right-6 -top-6 opacity-20">
                    <i data-lucide="bell" class="w-32 h-32"></i>
                </div>
                <div class="relative z-10">
                    <h4 class="text-xl font-extrabold tracking-tight mb-4">Ujian Aktif</h4>
                    <p class="text-sm text-rose-50 leading-relaxed mb-8">Anda memiliki {{ $cbtAktif->count() }} ujian yang sedang aktif. Segera kerjakan!</p>
                    <a href="{{ route('siswa.cbt.index') }}" class="px-6 py-3 bg-white text-rose-600 text-[10px] font-bold rounded-xl uppercase tracking-widest shadow-lg shadow-black/10 transition-all hover:scale-105 inline-block">Mulai Ujian</a>
                </div>
            </div>
            @endif

            <div class="card-soft p-8">
                <h4 class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-6">Wali Kelas Anda</h4>
                <div class="flex items-center gap-5">
                    @php
                        $wali = $siswa->kelas->waliKelas ?? null;
                    @endphp
                    @if($wali)
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($wali->nama) }}&background=fdf2f8&color=db2777" class="w-12 h-12 rounded-2xl">
                    <div>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $wali->nama }}</p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">NIP. {{ $wali->nip }}</p>
                    </div>
                    @else
                    <p class="text-xs text-slate-400 italic">Belum ditentukan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
