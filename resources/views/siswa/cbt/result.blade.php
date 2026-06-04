@extends('layouts.admin')

@section('title', 'Hasil Ujian')
@section('page_title', 'Evaluasi CBT')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Score Header -->
    <div class="card-soft overflow-hidden">
        <div class="p-10 bg-gradient-to-br from-slate-900 to-slate-800 text-white relative">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8 relative z-10">
                <div class="text-center md:text-left">
                    <h2 class="text-3xl font-black tracking-tight mb-2">{{ $ujian->nama_ujian }}</h2>
                    <p class="text-slate-400 font-medium">{{ $ujian->mapel }} • Selesai pada {{ $hasil->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-2">Skor Akhir</p>
                        <div class="w-24 h-24 rounded-3xl bg-white/10 flex items-center justify-center border border-white/10 backdrop-blur-md">
                            <span class="text-4xl font-black {{ $hasil->skor >= 75 ? 'text-green-400' : 'text-rose-500' }}">{{ $hasil->skor }}</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-2">Status</p>
                        <div class="px-6 py-3 rounded-2xl {{ $hasil->status === 'Selesai' ? 'bg-green-500/20 text-green-400' : 'bg-rose-500/20 text-rose-400' }} border border-white/5 font-bold uppercase tracking-widest text-[10px]">
                            {{ $hasil->status }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="absolute right-0 top-0 w-1/3 h-full bg-gradient-to-l from-rose-500/10 to-transparent pointer-events-none"></div>
        </div>

        <div class="p-10 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 bg-slate-50 dark:bg-white/5 rounded-2xl flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-500/20 flex items-center justify-center text-green-600">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jawaban Benar</p>
                    <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $hasil->jumlah_benar }} Soal</p>
                </div>
            </div>
            <div class="p-6 bg-slate-50 dark:bg-white/5 rounded-2xl flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-rose-100 dark:bg-rose-500/20 flex items-center justify-center text-rose-600">
                    <i data-lucide="x-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jawaban Salah</p>
                    <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $hasil->jumlah_salah }} Soal</p>
                </div>
            </div>
            <div class="p-6 bg-slate-50 dark:bg-white/5 rounded-2xl flex items-center gap-5">
                <div class="w-12 h-12 rounded-xl bg-sky-100 dark:bg-sky-500/20 flex items-center justify-center text-sky-600">
                    <i data-lucide="activity" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Akurasi</p>
                    <p class="text-xl font-bold text-slate-800 dark:text-white">{{ round(($hasil->jumlah_benar / ($hasil->jumlah_benar + $hasil->jumlah_salah)) * 100) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Recommendation -->
    <div class="card-soft p-10 border-l-8 border-rose-500">
        <div class="flex items-start gap-8">
            <div class="w-16 h-16 rounded-3xl bg-rose-100 dark:bg-rose-600/20 flex items-center justify-center text-rose-600 shrink-0">
                <i data-lucide="sparkles" class="w-8 h-8"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold uppercase tracking-widest text-slate-800 dark:text-white mb-4">Rekomendasi Evaluasi</h4>
                <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed italic">
                    "{{ $hasil->rekomendasi_ai }}"
                </p>
            </div>
        </div>
    </div>

    @if($hasil->status === 'Remedial')
    <div class="p-8 bg-amber-50 dark:bg-amber-600/10 rounded-3xl border border-amber-200 dark:border-amber-600/20 flex items-center justify-between">
        <div class="flex items-center gap-6">
            <div class="w-12 h-12 rounded-2xl bg-amber-500 flex items-center justify-center text-white">
                <i data-lucide="alert-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <h5 class="text-sm font-bold text-amber-800 dark:text-amber-400">Jadwal Remedial</h5>
                <p class="text-xs text-amber-700 dark:text-amber-500 mt-1">Silakan hubungi guru mata pelajaran {{ $ujian->mapel }} untuk informasi ujian susulan.</p>
            </div>
        </div>
        <button class="px-6 py-3 bg-amber-500 text-white text-[10px] font-bold rounded-xl uppercase tracking-widest hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/20">
            Hubungi Guru
        </button>
    </div>
    @endif

    <div class="flex justify-center pt-8">
        <a href="{{ route('siswa.cbt.index') }}" class="px-10 py-4 bg-white dark:bg-surface-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-2xl shadow-sm border border-slate-100 dark:border-white/5 transition-all hover:bg-slate-50 uppercase tracking-widest">
            Kembali ke Daftar Ujian
        </a>
    </div>
</div>
@endsection
