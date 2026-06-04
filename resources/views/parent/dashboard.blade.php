@extends('layouts.admin')

@section('title', 'Dasbor Orang Tua')
@section('page_title', 'Ringkasan Aktivitas Anak')

@section('content')
<div class="space-y-12">
    <!-- Student Info Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 bg-white/40 dark:bg-white/5 p-10 rounded-[2.5rem] border border-white dark:border-white/5 backdrop-blur-md">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-3xl overflow-hidden border-4 border-white shadow-xl shrink-0">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}&background=f43f5e&color=fff" class="w-full h-full object-cover">
            </div>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ $siswa->nama }}</h1>
                <p class="text-sm text-slate-400 font-medium mt-1">NISN: {{ $siswa->nisn }} • Kelas: {{ $siswa->kelas->nama_kelas ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('parent.raport.index') }}" class="px-6 py-4 bg-rose-600 text-white text-xs font-bold rounded-2xl shadow-lg shadow-rose-600/20 transition-all hover:scale-105 hover:bg-rose-700 uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="file-certificate" class="w-4 h-4"></i>
                E-Raport
            </a>
        </div>
    </div>

    <!-- Alert Section (Notifications) -->
    @if($notifications->count() > 0)
    <div class="space-y-4">
        <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400 ml-1">Pemberitahuan Terbaru</h3>
        <div class="grid grid-cols-1 gap-4">
            @foreach($notifications as $notif)
            <div class="p-6 bg-amber-50 dark:bg-amber-600/10 rounded-3xl border border-amber-200 dark:border-amber-600/20 flex items-center justify-between animate-in fade-in slide-in-from-left-4">
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500 flex items-center justify-center text-white shrink-0">
                        <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-900 dark:text-amber-400">{{ $notif->title }}</h4>
                        <p class="text-xs text-amber-700 dark:text-amber-500 mt-1">{{ $notif->message }}</p>
                    </div>
                </div>
                <span class="text-[10px] font-black text-amber-400 uppercase">{{ $notif->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Academic Summary -->
        <div class="card-soft overflow-hidden">
            <div class="p-8 border-b border-slate-50 dark:border-white/5 flex items-center justify-between bg-white/50 dark:bg-white/5">
                <h3 class="text-sm font-bold uppercase tracking-widest text-slate-900 dark:text-white">Nilai Terbaru</h3>
                <a href="{{ route('parent.nilai.index') }}" class="text-[10px] font-bold text-rose-600 uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>
            <div class="p-0">
                <div class="divide-y divide-slate-50 dark:divide-white/5">
                    @forelse($latestGrades as $nilai)
                    <div class="p-8 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-white/5 transition-all">
                        <div class="flex gap-6 items-center">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400 font-black text-lg">
                                {{ $nilai->nilai_angka }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $nilai->mapel }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Semester {{ $nilai->semester }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 {{ $nilai->nilai_angka >= 75 ? 'bg-green-100 text-green-600' : 'bg-rose-100 text-rose-600' }} text-[9px] font-black rounded-full uppercase">
                            {{ $nilai->nilai_angka >= 75 ? 'Tuntas' : 'Remedial' }}
                        </span>
                    </div>
                    @empty
                    <div class="p-10 text-center text-slate-400 text-sm">Belum ada data nilai.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="card-soft overflow-hidden">
            <div class="p-8 border-b border-slate-50 dark:border-white/5 flex items-center justify-between bg-white/50 dark:bg-white/5">
                <h3 class="text-sm font-bold uppercase tracking-widest text-slate-900 dark:text-white">Presensi Terakhir</h3>
                <i data-lucide="calendar" class="w-4 h-4 text-slate-300"></i>
            </div>
            <div class="p-0">
                <div class="divide-y divide-slate-50 dark:divide-white/5">
                    @forelse($latestAttendance as $att)
                    <div class="p-8 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-white/5 transition-all">
                        <div class="flex gap-6 items-center">
                            <div class="w-12 h-12 rounded-2xl {{ $att->status === 'Hadir' ? 'bg-green-100 text-green-600' : 'bg-rose-100 text-rose-600' }} flex items-center justify-center">
                                <i data-lucide="{{ $att->status === 'Hadir' ? 'user-check' : 'user-x' }}" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $att->created_at->translatedFormat('d F Y') }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Status: {{ $att->status }}</p>
                            </div>
                        </div>
                        @if($att->menit_terlambat > 0)
                        <span class="px-3 py-1 bg-amber-100 text-amber-600 text-[9px] font-black rounded-full uppercase">
                            Terlambat {{ $att->menit_terlambat }}m
                        </span>
                        @endif
                    </div>
                    @empty
                    <div class="p-10 text-center text-slate-400 text-sm">Belum ada data kehadiran.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
