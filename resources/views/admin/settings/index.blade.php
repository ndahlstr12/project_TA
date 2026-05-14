@extends('layouts.admin')

@section('title', 'Konfigurasi Akademik')
@section('page_title', 'Pengaturan Sistem')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter text-slate-900 dark:text-white">Konfigurasi Akademik</h1>
            <p class="text-sm text-neutral-500 mt-2">Atur periode aktif untuk Tahun Ajaran dan Semester guna sinkronisasi data raport.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-blue-500/10 text-blue-600 rounded-xl border border-blue-500/20 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                Mode Konfigurasi
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center gap-4 animate-in fade-in slide-in-from-top duration-500">
        <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
            <i data-lucide="check" class="w-5 h-5"></i>
        </div>
        <p class="text-xs font-bold text-emerald-600 tracking-tight">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Form Configuration -->
    <div class="card-pro overflow-hidden">
        <div class="p-8 border-b border-base bg-neutral-50/30 dark:bg-white/5">
            <h3 class="text-xs font-black uppercase tracking-widest text-neutral-400">Parameter Periode Aktif</h3>
        </div>
        
        <form action="{{ route('admin.settings.update') }}" method="POST" class="p-8 space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Tahun Ajaran -->
                <div class="space-y-3">
                    <label class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest flex items-center gap-2">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-neutral-400"></i>
                        Tahun Ajaran Aktif
                    </label>
                    <div class="relative group">
                        <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $tahun_ajaran) }}" 
                               class="w-full pl-5 pr-5 py-4 bg-neutral-50 dark:bg-white/5 border border-base rounded-2xl text-sm font-bold focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                               placeholder="Contoh: 2025/2026" required>
                    </div>
                    <p class="text-[10px] text-neutral-400 font-medium italic">*Format disarankan: YYYY/YYYY (Contoh: 2025/2026)</p>
                </div>

                <!-- Semester -->
                <div class="space-y-3">
                    <label class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest flex items-center gap-2">
                        <i data-lucide="layers" class="w-3.5 h-3.5 text-neutral-400"></i>
                        Semester Aktif
                    </label>
                    <div class="relative group">
                        <select name="semester" class="w-full px-5 py-4 bg-neutral-50 dark:bg-white/5 border border-base rounded-2xl text-sm font-bold focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none appearance-none cursor-pointer" required>
                            <option value="Ganjil" {{ $semester == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                            <option value="Genap" {{ $semester == 'Genap' ? 'selected' : '' }}>Semester Genap</option>
                        </select>
                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-neutral-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-neutral-400 font-medium italic">*Semester aktif akan menentukan input nilai raport.</p>
                </div>
            </div>

            <div class="pt-6 border-t border-base flex items-center justify-between">
                <div class="flex items-center gap-3 text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    Pembaruan Data Real-time
                </div>
                <button type="submit" class="px-8 py-4 bg-neutral-950 dark:bg-white text-white dark:text-neutral-950 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-2xl active:scale-95 transition-all flex items-center gap-3">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Info Panel -->
    <div class="p-8 bg-amber-50 dark:bg-amber-500/5 border border-amber-200 dark:border-amber-500/20 rounded-[2rem] flex items-start gap-6">
        <div class="w-14 h-14 rounded-2xl bg-amber-500 flex items-center justify-center text-white shrink-0 shadow-xl shadow-amber-500/20">
            <i data-lucide="alert-triangle" class="w-7 h-7"></i>
        </div>
        <div>
            <h4 class="text-sm font-black text-amber-900 dark:text-amber-400 uppercase tracking-widest mb-2">Peringatan Penting</h4>
            <p class="text-xs text-amber-700/80 dark:text-amber-500/70 leading-relaxed font-medium">
                Mengubah Tahun Ajaran atau Semester secara mendadak dapat menyebabkan ketidakkonsistenan pada tampilan raport siswa jika nilai sudah mulai diinput. Pastikan koordinasi dengan Wali Kelas sebelum melakukan perubahan krusial pada pertengahan periode akademik.
            </p>
        </div>
    </div>
</div>
@endsection
