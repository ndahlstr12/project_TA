@extends('layouts.admin')

@section('title', 'E-Raport Siswa')
@section('page_title', 'Laporan Hasil Belajar')

@section('content')
<div class="space-y-8">
    <div class="card-soft p-10 bg-white dark:bg-surface-800 border-none shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
            <div class="w-24 h-24 rounded-3xl bg-rose-50 dark:bg-rose-600/10 flex items-center justify-center text-rose-600 shrink-0">
                <i data-lucide="graduation-cap" class="w-12 h-12"></i>
            </div>
            <div class="text-center md:text-left">
                <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">E-Raport Digital</h2>
                <p class="text-slate-500 mt-2 leading-relaxed max-w-xl">
                    Selamat datang di portal raport digital. Di sini Anda dapat melihat dan mengunduh laporan hasil belajar Anda setiap semester.
                </p>
            </div>
        </div>
        <div class="absolute right-0 top-0 w-64 h-64 bg-rose-500/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($raports as $raport)
        <div class="card-soft group hover:scale-[1.02] transition-all duration-500 overflow-hidden">
            <div class="p-8 border-b border-slate-50 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 bg-rose-100 text-rose-600 text-[10px] font-bold rounded-full uppercase tracking-widest">
                        Semester {{ $raport->semester }}
                    </span>
                    <i data-lucide="file-text" class="w-5 h-5 text-slate-300"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Tahun Ajaran {{ $raport->tahun_ajaran }}</h3>
            </div>
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kehadiran</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $raport->kehadiran_presentase }}%</p>
                    </div>
                    <div class="space-y-1 text-right">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Rata-rata</p>
                        <p class="text-sm font-bold text-rose-600">{{ $raport->rata_rata_nilai }}</p>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Saran AI</p>
                    <p class="text-xs text-slate-500 leading-relaxed line-clamp-2 italic">
                        "{{ $raport->saran_ai }}"
                    </p>
                </div>

                <div class="pt-4 flex gap-3">
                    <a href="{{ route('walikelas.raport.export-pdf', $raport->id) }}" class="flex-1 py-3 bg-rose-600 text-white text-[10px] font-bold rounded-xl uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-rose-700 shadow-lg shadow-rose-600/20 transition-all">
                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                        Unduh PDF
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 flex flex-col items-center justify-center bg-white dark:bg-white/5 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-white/10">
            <div class="w-20 h-20 bg-slate-50 dark:bg-white/5 rounded-3xl flex items-center justify-center text-slate-300 mb-4">
                <i data-lucide="file-question" class="w-10 h-10"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Raport Belum Tersedia</h3>
            <p class="text-sm text-slate-500 mt-1 text-center max-w-xs">Laporan hasil belajar Anda belum diterbitkan oleh wali kelas.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
