@extends('layouts.admin')

@section('title', 'Orang Tua Dashboard')
@section('page_title', 'Parental Overview')

@section('content')
<div class="space-y-10">
    
    <!-- Pro Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter">Selamat Datang, {{ Auth::user()->name }}</h1>
            <p class="text-sm text-neutral-500 mt-2 uppercase tracking-widest font-bold">Portal Orang Tua &bull; Monitoring Akademik Anak</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-pro p-6 flex items-center gap-5 group hover:border-emerald-500 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest mb-1">Kehadiran Anak</p>
                <p class="text-2xl font-bold tracking-tighter text-neutral-900 dark:text-white">100%</p>
            </div>
        </div>

        <div class="card-pro p-6 flex items-center gap-5 group hover:border-violet-500 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-violet-500/10 flex items-center justify-center text-violet-500">
                <i data-lucide="line-chart" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest mb-1">Rata-rata Nilai</p>
                <p class="text-2xl font-bold tracking-tighter text-neutral-900 dark:text-white">88.0</p>
            </div>
        </div>

        <div class="card-pro p-6 flex items-center gap-5 group hover:border-pink-500 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-pink-500/10 flex items-center justify-center text-pink-500">
                <i data-lucide="bell-ring" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest mb-1">Pengumuman Baru</p>
                <p class="text-2xl font-bold tracking-tighter text-neutral-900 dark:text-white">1 Pesan</p>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="card-pro p-8">
            <h3 class="text-sm font-bold uppercase tracking-widest mb-6">Informasi Siswa</h3>
            <div class="space-y-6">
                <div class="flex items-center gap-4 p-4 bg-neutral-50 dark:bg-white/5 rounded-xl border border-base">
                    <div class="w-12 h-12 rounded-lg bg-white dark:bg-surface-800 flex items-center justify-center border border-base">
                        <i data-lucide="graduation-cap" class="w-6 h-6 text-neutral-400"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-neutral-500 uppercase font-bold tracking-widest">Nama Anak</p>
                        <p class="text-sm font-bold">Andi Saputra</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-neutral-50 dark:bg-white/5 rounded-xl border border-base">
                        <p class="text-[10px] text-neutral-500 uppercase font-bold tracking-widest">Kelas</p>
                        <p class="text-sm font-bold">X-RPL 1</p>
                    </div>
                    <div class="p-4 bg-neutral-50 dark:bg-white/5 rounded-xl border border-base">
                        <p class="text-[10px] text-neutral-500 uppercase font-bold tracking-widest">NISN</p>
                        <p class="text-sm font-bold">0092348123</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-pro p-8 bg-surface-900 text-white relative overflow-hidden border-none">
            <div class="absolute right-0 bottom-0 opacity-10">
                <i data-lucide="shield-check" class="w-64 h-64"></i>
            </div>
            <h3 class="text-lg font-bold tracking-tighter mb-4 relative z-10">Keamanan & Privasi</h3>
            <p class="text-sm opacity-60 leading-relaxed mb-8 relative z-10">Data akademik anak Anda dilindungi dengan enkripsi standar industri. Pastikan Anda mengganti kata sandi secara berkala untuk menjaga kerahasiaan informasi.</p>
            <button class="px-5 py-2.5 bg-white text-neutral-950 text-[10px] font-bold rounded-lg uppercase tracking-widest relative z-10">Pengaturan Akun</button>
        </div>
    </div>
</div>
@endsection

