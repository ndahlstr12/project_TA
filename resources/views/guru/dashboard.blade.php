@extends('layouts.admin')

@section('title', 'Beranda Guru')
@section('page_title', 'Dashboard Guru Mata Pelajaran')

@section('content')
<div class="space-y-6 md:space-y-8">
    
    <!-- Header Selamat Datang -->
    <div class="relative overflow-hidden rounded-[1.5rem] md:rounded-[2.5rem] bg-slate-900 p-6 md:p-10 text-white shadow-2xl">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-xl">
                <div class="flex items-center gap-3 mb-3">
                    <span class="px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-[8px] md:text-[9px] font-black uppercase tracking-widest border border-white/10 text-slate-300">Guru Mata Pelajaran</span>
                </div>
                <h1 class="text-2xl md:text-4xl font-black tracking-tighter leading-tight mb-3">
                    Halo, <span class="text-blue-400">{{ explode(' ', Auth::user()->name)[0] }}</span>!
                </h1>
                <p class="text-slate-300 text-xs md:text-sm font-medium opacity-80 leading-relaxed">
                    {{ $semester }} | {{ $tahunAjaran }}. Kelola agenda mengajar dan input nilai siswa dengan mudah.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('shared.nilai.index') }}" class="flex items-center justify-center gap-2 bg-white text-slate-900 px-6 py-3 md:py-4 rounded-xl md:rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest hover:bg-blue-400 hover:text-white transition-all duration-300 shadow-xl active:scale-95">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    Input Nilai Mapel
                </a>
            </div>
        </div>
        <div class="absolute top-0 right-0 w-48 h-48 md:w-64 md:h-64 bg-blue-500/10 rounded-full blur-[60px] md:blur-[80px] -translate-y-1/2 translate-x-1/2"></div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <!-- Total Siswa -->
        <div class="card-pro p-4 md:p-6 flex flex-col md:flex-row items-start md:items-center gap-3 md:gap-4 bg-white dark:bg-slate-900">
            <div class="w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                <i data-lucide="users" class="w-5 h-5 md:w-7 md:h-7"></i>
            </div>
            <div>
                <p class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">Siswa Diajar</p>
                <p class="text-lg md:text-2xl font-black text-slate-900 dark:text-white">{{ $stats['total_siswa'] }}</p>
            </div>
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="card-pro p-4 md:p-6 flex flex-col md:flex-row items-start md:items-center gap-3 md:gap-4 bg-white dark:bg-slate-900">
            <div class="w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                <i data-lucide="calendar" class="w-5 h-5 md:w-7 md:h-7"></i>
            </div>
            <div>
                <p class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">Sesi Hari Ini</p>
                <p class="text-lg md:text-2xl font-black text-slate-900 dark:text-white">{{ $stats['jadwal_today'] }}</p>
            </div>
        </div>

        <!-- Total Kelas (Tampil di tablet/desktop) -->
        <div class="hidden sm:flex card-pro p-4 md:p-6 flex-col md:flex-row items-start md:items-center gap-3 md:gap-4 bg-white dark:bg-slate-900">
            <div class="w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-500">
                <i data-lucide="layout" class="w-5 h-5 md:w-7 md:h-7"></i>
            </div>
            <div>
                <p class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Kelas</p>
                <p class="text-lg md:text-2xl font-black text-slate-900 dark:text-white">{{ $stats['total_kelas'] }}</p>
            </div>
        </div>
    </div>

    <!-- Layout Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8" x-data="{ activeTab: 'progres' }">
        
        <!-- Panel Utama -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Tab Navigasi -->
            <div class="flex bg-slate-100 dark:bg-white/5 p-1 rounded-xl md:rounded-2xl border border-base inline-flex w-full md:w-auto">
                <button @click="activeTab = 'progres'" :class="activeTab === 'progres' ? 'bg-white dark:bg-slate-800 text-blue-600 shadow-sm' : 'text-slate-400'" class="flex-1 md:flex-none px-4 md:px-6 py-2 md:py-2.5 text-[9px] md:text-[10px] font-black uppercase tracking-widest rounded-lg md:rounded-xl transition-all">
                    Progres Nilai
                </button>
                <button @click="activeTab = 'jadwal'" :class="activeTab === 'jadwal' ? 'bg-white dark:bg-slate-800 text-blue-600 shadow-sm' : 'text-slate-400'" class="flex-1 md:flex-none px-4 md:px-6 py-2 md:py-2.5 text-[9px] md:text-[10px] font-black uppercase tracking-widest rounded-lg md:rounded-xl transition-all">
                    Semua Jadwal
                </button>
            </div>

            <!-- Konten Tab: Progres Nilai -->
            <div x-show="activeTab === 'progres'" x-transition class="space-y-4">
                <div class="card-pro p-4 md:p-6 bg-white dark:bg-slate-900">
                    <h4 class="text-xs md:text-sm font-black uppercase tracking-widest text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-blue-500"></i>
                        Penyelesaian Nilai Akademik
                    </h4>
                    <div class="space-y-6">
                        @forelse($progresNilai as $prog)
                        <div class="space-y-2">
                            <div class="flex justify-between items-start text-[10px] md:text-[11px] font-bold">
                                <span class="text-slate-700 dark:text-slate-300 flex-1 pr-4">{{ $prog['mapel'] }} - <span class="text-blue-500">{{ $prog['kelas'] }}</span></span>
                                <span class="{{ $prog['progres'] == 100 ? 'text-emerald-500' : 'text-blue-500' }}">{{ $prog['progres'] }}%</span>
                            </div>
                            <div class="h-1.5 md:h-2 w-full bg-slate-100 dark:bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full {{ $prog['progres'] == 100 ? 'bg-emerald-500' : 'bg-blue-500' }} rounded-full transition-all duration-700" style="width: {{ $prog['progres'] }}%"></div>
                            </div>
                            <div class="flex justify-between items-center text-[8px] text-slate-400 uppercase font-black tracking-tighter">
                                <span>{{ $prog['count'] }} / {{ $prog['total'] }} Siswa</span>
                                <span>{{ $prog['hari'] }}, {{ $prog['jam'] }}</span>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-center text-slate-400 italic py-4">Belum ada data.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Konten Tab: Seluruh Jadwal -->
            <div x-show="activeTab === 'jadwal'" x-transition class="space-y-4">
                @forelse($schedules as $s)
                <div class="card-pro p-4 md:p-5 bg-white dark:bg-slate-900 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-slate-50 dark:bg-white/5 rounded-xl md:rounded-2xl border border-base">
                            <span class="text-[8px] md:text-[10px] font-black text-blue-500 uppercase">{{ substr($s->hari, 0, 3) }}</span>
                            <span class="text-[10px] md:text-xs font-black text-slate-400">{{ $s->jam_mulai }}</span>
                        </div>
                        <div>
                            <h5 class="text-xs md:text-sm font-black text-slate-800 dark:text-white leading-tight">{{ $s->mapel->nama_mapel ?? '-' }}</h5>
                            <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">Kelas: {{ $s->kelas->nama_kelas ?? '-' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-emerald-500/5 text-emerald-500 text-[8px] font-black uppercase rounded-lg">Aktif</span>
                </div>
                @empty
                <div class="p-10 text-center opacity-30">
                    <i data-lucide="calendar-off" class="w-10 h-10 mx-auto mb-2"></i>
                    <p class="text-[10px] font-bold uppercase tracking-widest">Kosong</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar / Bawah Mobile -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Sesi Hari Ini -->
            <div class="card-pro p-6 bg-white dark:bg-slate-900 border-t-4 border-t-blue-500">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-6">Sesi Hari Ini</h4>
                <div class="space-y-4">
                    @forelse($jadwalHariIni as $jh)
                    <div class="flex items-start gap-4 p-3 bg-slate-50 dark:bg-white/5 rounded-xl border border-base">
                        <div class="text-[10px] font-black text-blue-500 py-1">{{ $jh->jam_mulai }}</div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-slate-800 dark:text-white leading-tight">{{ $jh->mapel->nama_mapel ?? '-' }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">{{ $jh->kelas->nama_kelas ?? '-' }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-[10px] text-center text-slate-400 font-bold uppercase py-4">Tidak ada sesi</p>
                    @endforelse
                </div>
                <div class="mt-8">
                    <a href="{{ route('shared.kehadiran.index') }}" class="w-full flex items-center justify-center gap-2 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg">
                        <i data-lucide="user-check" class="w-4 h-4"></i>
                        Input Absensi
                    </a>
                </div>
            </div>

            <!-- Tips Mobile -->
            <div class="p-6 bg-indigo-600 rounded-[2rem] text-white shadow-xl relative overflow-hidden group">
                <div class="relative z-10">
                    <i data-lucide="lightbulb" class="w-6 h-6 mb-3 text-amber-400"></i>
                    <h5 class="text-[11px] font-black uppercase tracking-widest mb-1">Tips Mobile</h5>
                    <p class="text-[10px] leading-relaxed text-indigo-100 font-medium">
                        Gunakan tombol di atas untuk absensi cepat langsung dari smartphone saat berada di kelas.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush