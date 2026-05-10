@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page_title', 'Pengaturan Profil')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">
    
    <!-- Pro Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter">Konfigurasi Profil</h1>
            <p class="text-sm text-neutral-500 mt-2 uppercase tracking-widest font-bold">Personalisasi Identitas & Keamanan Akun</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white">
            <i data-lucide="check" class="w-4 h-4"></i>
        </div>
        <p class="text-xs font-bold text-emerald-600 tracking-tight">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card-pro overflow-hidden">
                <div class="h-32 bg-neutral-900 dark:bg-black relative">
                    <div class="absolute inset-0 opacity-20 flex items-center justify-center overflow-hidden">
                        <i data-lucide="command" class="w-48 h-48 text-white"></i>
                    </div>
                </div>
                <div class="px-6 pb-8 -mt-12 text-center relative z-10">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0a0a0a&color=fff&size=128" 
                         alt="Profile" 
                         class="w-24 h-24 rounded-2xl mx-auto border-4 border-white dark:border-surface-800 shadow-xl mb-4 grayscale dark:invert">
                    <h3 class="text-xl font-bold tracking-tighter">{{ Auth::user()->name }}</h3>
                    <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-[0.2em] mt-2">{{ Auth::user()->role }}</p>
                </div>
            </div>

            <div class="card-pro p-6">
                <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-6">Status Keanggotaan</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-base">
                        <span class="text-[11px] font-medium text-neutral-500">Terdaftar Sejak</span>
                        <span class="text-xs font-bold">{{ Auth::user()->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-base">
                        <span class="text-[11px] font-medium text-neutral-500">Status Akun</span>
                        <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-500 text-[10px] font-bold rounded uppercase">Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Update Profil -->
            <div class="card-pro p-8 lg:p-10">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center text-accent">
                        <i data-lucide="user-cog" class="w-4 h-4"></i>
                    </div>
                    <h4 class="text-sm font-bold uppercase tracking-widest">Informasi Dasar</h4>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ Auth::user()->name }}" 
                                class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border border-base rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Username / ID</label>
                            <input type="text" name="username" value="{{ Auth::user()->username }}" 
                                class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border border-base rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Email Sistem</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" 
                            class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border border-base rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all">
                    </div>

                    @if((Auth::user()->role === 'siswa' || Auth::user()->role === 'orangtua') && Auth::user()->siswa)
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Email Orang Tua (Notifikasi Raport)</label>
                        <input type="email" name="email_orang_tua" value="{{ Auth::user()->siswa->email_orang_tua }}" 
                            class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border border-base rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all" placeholder="contoh@gmail.com">
                        <p class="text-[9px] text-neutral-400 italic mt-2">* Digunakan untuk transmisi dokumen raport PDF secara digital.</p>
                    </div>
                    @endif

                    <div class="pt-4">
                        <button type="submit" class="px-8 py-3.5 bg-neutral-950 dark:bg-white text-white dark:text-neutral-950 text-[10px] font-bold rounded-lg uppercase tracking-widest hover:opacity-90 transition-all active:scale-[0.98]">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Ganti Password -->
            <div class="card-pro p-8 lg:p-10">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500">
                        <i data-lucide="shield-lock" class="w-4 h-4"></i>
                    </div>
                    <h4 class="text-sm font-bold uppercase tracking-widest">Kredensial Keamanan</h4>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" 
                            class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border border-base rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Kata Sandi Baru</label>
                            <input type="password" name="password" 
                                class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border border-base rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Konfirmasi Sandi</label>
                            <input type="password" name="password_confirmation" 
                                class="w-full px-4 py-3 bg-neutral-50 dark:bg-white/5 border border-base rounded-lg text-sm font-medium focus:border-neutral-950 dark:focus:border-white outline-none transition-all" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="px-8 py-3.5 bg-amber-500 text-white text-[10px] font-bold rounded-lg uppercase tracking-widest hover:bg-amber-600 transition-all active:scale-[0.98]">
                            Perbarui Kredensial
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

