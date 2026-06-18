@extends('layouts.admin')

@section('title', 'Dasbor Orang Tua')
@section('page_title', 'Ringkasan Aktivitas Anak')

@section('content')
<!-- Email Warning Modal (Animation) -->
@if(!$siswa->email_orang_tua)
<div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-500"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-2xl border border-rose-500/20 max-w-lg w-full overflow-hidden animate-in zoom-in duration-500 delay-200">
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-rose-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="p-10 text-center relative z-10">
            <div class="w-24 h-24 bg-rose-100 dark:bg-rose-500/10 rounded-3xl flex items-center justify-center mx-auto mb-8 animate-bounce">
                <i data-lucide="mail" class="w-12 h-12 text-rose-600"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-4">Email Wajib Diisi!</h2>
            <p class="text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                Kami mendeteksi Anda belum mengisi <strong>Email Orang Tua</strong>. Email ini sangat penting untuk menerima salinan raport digital secara otomatis.
            </p>
            <div class="flex flex-col gap-4">
                <a href="{{ route('profile.index') }}" class="w-full py-4 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-2xl shadow-lg shadow-rose-600/30 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="user-cog" class="w-5 h-5"></i>
                    Lengkapi Profil Sekarang
                </a>
                <button @click="open = false" class="w-full py-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-bold transition-all text-sm uppercase tracking-widest">
                    Nanti Saja
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<div class="space-y-12">
    <!-- Student Info Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 bg-white/40 dark:bg-white/5 p-6 md:p-10 rounded-[2rem] md:rounded-[2.5rem] border border-white dark:border-white/5 backdrop-blur-md">
        <div class="flex items-center gap-4 md:gap-6">
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl md:rounded-3xl overflow-hidden border-4 border-white shadow-xl shrink-0">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}&background=f43f5e&color=fff" class="w-full h-full object-cover">
            </div>
            <div class="min-w-0">
                <h1 class="text-xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white truncate">{{ $siswa->nama }}</h1>
                <p class="text-[10px] md:text-sm text-slate-400 font-medium mt-1">NISN: {{ $siswa->nisn }} • Kelas: {{ $siswa->kelas->nama_kelas ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex gap-4 w-full lg:w-auto">
            <a href="{{ route('parent.raport.index') }}" class="flex-1 lg:flex-none px-6 py-4 bg-rose-600 text-white text-[10px] md:text-xs font-bold rounded-2xl shadow-lg shadow-rose-600/20 transition-all hover:scale-105 hover:bg-rose-700 uppercase tracking-widest flex items-center justify-center gap-2">
                <i data-lucide="file-certificate" class="w-4 h-4"></i>
                E-Raport
            </a>
        </div>
    </div>

    <!-- Alert Section (Notifications) -->
    @if($notifications->count() > 0)
    <div class="space-y-4">
        <h3 class="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Pemberitahuan Terbaru</h3>
        <div class="grid grid-cols-1 gap-4">
            @foreach($notifications as $notif)
            <div class="p-5 md:p-6 bg-amber-50 dark:bg-amber-600/10 rounded-3xl border border-amber-200 dark:border-amber-600/20 flex flex-col md:flex-row md:items-center justify-between gap-4 animate-in fade-in slide-in-from-left-4">
                <div class="flex items-start md:items-center gap-4 md:gap-6">
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl bg-amber-500 flex items-center justify-center text-white shrink-0">
                        <i data-lucide="alert-triangle" class="w-5 h-5 md:w-6 md:h-6"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-900 dark:text-amber-400 leading-tight">{{ $notif->title }}</h4>
                        <p class="text-xs text-amber-700 dark:text-amber-500 mt-1 leading-relaxed">{{ $notif->message }}</p>
                    </div>
                </div>
                <span class="text-[9px] md:text-[10px] font-black text-amber-400 uppercase md:whitespace-nowrap">{{ $notif->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- CBT Summary -->
        <div class="card-soft overflow-hidden">
            <div class="p-8 border-b border-slate-50 dark:border-white/5 flex items-center justify-between bg-white/50 dark:bg-white/5">
                <h3 class="text-sm font-bold uppercase tracking-widest text-slate-900 dark:text-white">Hasil Ujian CBT Terbaru</h3>
                <a href="{{ route('parent.nilai.index') }}" class="text-[10px] font-bold text-rose-600 uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>
            <div class="p-0">
                <div class="divide-y divide-slate-50 dark:divide-white/5">
                    @forelse($latestCbt as $hasil)
                    <div class="p-8 flex items-center justify-between hover:bg-slate-50/50 dark:hover:bg-white/5 transition-all">
                        <div class="flex gap-6 items-center">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400 font-black text-lg">
                                {{ round($hasil->skor) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $hasil->ujian->nama_ujian }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $hasil->ujian->mapel }} • {{ $hasil->created_at->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 {{ $hasil->status === 'Selesai' ? 'bg-green-100 text-green-600' : 'bg-rose-100 text-rose-600' }} text-[9px] font-black rounded-full uppercase">
                            {{ $hasil->status }}
                        </span>
                    </div>
                    @empty
                    <div class="p-10 text-center text-slate-400 text-sm">Belum ada hasil ujian CBT terbaru.</div>
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

    <!-- Behavior Summary Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400">Catatan Perilaku Terbaru</h3>
            <a href="{{ route('parent.jurnal.index') }}" class="text-[10px] font-bold text-rose-600 uppercase tracking-widest hover:underline">Lihat Selengkapnya</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($latestBehaviors as $behavior)
            <div class="card-soft p-6 border-l-4 {{ $behavior->tipe === 'Positif' ? 'border-l-emerald-500' : 'border-l-rose-500' }} hover:scale-[1.02] transition-all">
                <div class="flex items-center justify-between mb-4">
                    <span class="px-2 py-0.5 {{ $behavior->tipe === 'Positif' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }} text-[9px] font-black rounded-md uppercase tracking-tighter">
                        {{ $behavior->tipe }}
                    </span>
                    <span class="text-[10px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($behavior->tanggal)->translatedFormat('d M Y') }}</span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400 font-medium leading-relaxed line-clamp-3">
                    {{ $behavior->catatan }}
                </p>
                @if($behavior->rekomendasi)
                <div class="mt-3 p-3 bg-blue-500/5 rounded-xl border border-blue-500/10">
                    <p class="text-[8px] font-black text-blue-500 uppercase tracking-widest mb-1">Saran Guru:</p>
                    <p class="text-[10px] text-slate-500 line-clamp-2 leading-tight">{{ $behavior->rekomendasi }}</p>
                </div>
                @endif
                <div class="mt-4 pt-4 border-t border-slate-50 dark:border-white/5 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center">
                        <i data-lucide="user" class="w-3 h-3 text-slate-400"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-500">{{ $behavior->guru->nama ?? 'Wali Kelas' }}</span>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center card-soft opacity-50">
                <p class="text-xs font-bold uppercase tracking-widest">Belum ada catatan perilaku terbaru</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
