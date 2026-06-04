@extends('layouts.admin')

@section('title', 'Persiapan Ujian')
@section('page_title', 'Konfirmasi Ujian')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card-soft overflow-hidden">
        <div class="p-8 bg-gradient-to-br from-rose-500 to-pink-600 text-white border-none relative">
            <div class="relative z-10">
                <h2 class="text-2xl font-black tracking-tight mb-2">{{ $ujian->nama_ujian }}</h2>
                <p class="text-rose-100 text-sm font-medium">{{ $ujian->mapel }} • {{ $ujian->kelas }}</p>
            </div>
            <div class="absolute right-8 top-1/2 -translate-y-1/2 opacity-20">
                <i data-lucide="info" class="w-24 h-24"></i>
            </div>
        </div>
        
        <div class="p-10 space-y-10">
            <div>
                <h4 class="text-sm font-bold uppercase tracking-widest text-slate-800 dark:text-white mb-6">Aturan & Petunjuk</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-600/10 flex items-center justify-center text-rose-600 shrink-0">
                            <i data-lucide="timer" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Waktu Pengerjaan</p>
                            <p class="text-[11px] text-slate-500 mt-1">Ujian ini memiliki durasi {{ $ujian->durasi }} menit.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-600/10 flex items-center justify-center text-rose-600 shrink-0">
                            <i data-lucide="help-circle" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Jumlah Soal</p>
                            <p class="text-[11px] text-slate-500 mt-1">Terdapat {{ $ujian->jumlah_soal }} soal pilihan ganda.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-600/10 flex items-center justify-center text-rose-600 shrink-0">
                            <i data-lucide="shuffle" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Metode Acak</p>
                            <p class="text-[11px] text-slate-500 mt-1">Soal dan jawaban akan ditampilkan secara acak.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-600/10 flex items-center justify-center text-rose-600 shrink-0">
                            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Satu Kali Kesempatan</p>
                            <p class="text-[11px] text-slate-500 mt-1">Pastikan koneksi internet stabil sebelum memulai.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed italic">
                    "Dengan menekan tombol 'Mulai Sekarang', Anda menyatakan siap untuk mengikuti ujian ini dengan jujur dan mematuhi segala peraturan yang berlaku."
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('siswa.cbt.index') }}" class="flex-1 py-4 bg-white dark:bg-surface-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-2xl shadow-sm border border-slate-100 dark:border-white/5 transition-all hover:bg-slate-50 text-center uppercase tracking-widest">
                    Kembali
                </a>
                <a href="{{ route('siswa.cbt.test', $ujian->id) }}" class="flex-[2] py-4 bg-rose-600 text-white text-xs font-bold rounded-2xl shadow-lg shadow-rose-600/20 transition-all hover:bg-rose-700 hover:scale-[1.02] text-center uppercase tracking-widest flex items-center justify-center gap-2">
                    <i data-lucide="play" class="w-4 h-4 fill-current"></i>
                    Mulai Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
