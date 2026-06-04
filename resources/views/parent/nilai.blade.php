@extends('layouts.admin')

@section('title', 'Nilai Anak')
@section('page_title', 'Laporan Capaian Kompetensi')

@section('content')
<div class="space-y-8">
    <div class="card-soft p-10 bg-white dark:bg-surface-800 border-none shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
            <div class="w-24 h-24 rounded-3xl bg-sky-50 dark:bg-sky-600/10 flex items-center justify-center text-sky-600 shrink-0">
                <i data-lucide="book-open" class="w-12 h-12"></i>
            </div>
            <div class="text-center md:text-left">
                <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Data Nilai Siswa</h2>
                <p class="text-slate-500 mt-2 leading-relaxed max-w-xl">
                    Pantau perkembangan akademik anak Anda. Daftar di bawah menampilkan nilai dari berbagai mata pelajaran.
                </p>
            </div>
        </div>
        <div class="absolute right-0 top-0 w-64 h-64 bg-sky-500/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($nilais as $nilai)
        <div class="card-soft p-8 group hover:border-sky-500/30 transition-all duration-300">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">{{ $nilai->mapel }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Semester {{ $nilai->semester }} • {{ $nilai->tahun_ajaran }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl {{ $nilai->nilai_angka >= 75 ? 'bg-green-100 text-green-600' : 'bg-rose-100 text-rose-600' }} flex items-center justify-center font-black text-lg">
                    {{ $nilai->nilai_angka }}
                </div>
            </div>
            
            <div class="pt-6 border-t border-slate-50 dark:border-white/5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status Kelulusan</span>
                    <span class="px-3 py-1 {{ $nilai->nilai_angka >= 75 ? 'bg-green-100 text-green-600' : 'bg-rose-100 text-rose-600' }} text-[9px] font-black rounded-full uppercase">
                        {{ $nilai->nilai_angka >= 75 ? 'Tuntas' : 'Perlu Remedial' }}
                    </span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 flex flex-col items-center justify-center bg-white dark:bg-white/5 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-white/10">
            <div class="w-20 h-20 bg-slate-50 dark:bg-white/5 rounded-3xl flex items-center justify-center text-slate-300 mb-4">
                <i data-lucide="book-x" class="w-10 h-10"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Nilai Kosong</h3>
            <p class="text-sm text-slate-500 mt-1">Belum ada data nilai untuk anak Anda.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
