@extends('layouts.admin')

@section('title', 'Guru Dashboard')
@section('page_title', 'Ringkasan Aktivitas')

@section('content')
<div class="space-y-8">
    
    <!-- Pro Header (Sama dengan Admin) -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter text-slate-900 dark:text-white">Dashboard Guru</h1>
            <p class="text-sm text-neutral-500 mt-2">Selamat datang kembali, {{ Auth::user()->name }}. Kelola agenda pengajaran Anda.</p>
        </div>
        <div class="flex gap-3">
            <button class="px-4 py-2 text-xs font-bold border border-base rounded-lg hover:bg-neutral-50 dark:hover:bg-white/5 transition-all flex items-center gap-2">
                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                Rekap Nilai
            </button>
        </div>
    </div>

    <!-- Data Surface (Tabel High-Density Seperti Admin) -->
    <div class="card-pro overflow-hidden">
        <!-- Control Bar -->
        <div class="p-4 border-b border-base bg-neutral-50/30 dark:bg-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Agenda Pengajaran Terdaftar</h3>
            </div>
            
            <div class="flex items-center bg-white dark:bg-surface-900 border border-base rounded-lg px-3 py-1.5 gap-2 focus-within:ring-2 focus-within:ring-accent/10 transition-all">
                <i data-lucide="search" class="w-3.5 h-3.5 text-neutral-400"></i>
                <input type="text" placeholder="Cari jadwal atau kelas..." class="bg-transparent border-none focus:ring-0 text-xs font-medium w-48">
            </div>
        </div>

        <!-- High-Density Registry Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-neutral-50/30 dark:bg-white/5 border-b border-base text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                        <th class="px-8 py-4">Waktu & Sesi</th>
                        <th class="px-8 py-4">Mata Pelajaran</th>
                        <th class="px-8 py-4">Lokasi / Kelas</th>
                        <th class="px-8 py-4">Hari</th>
                        <th class="px-8 py-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base">
                    @forelse($schedules as $s)
                    <tr class="hover:bg-neutral-50/30 dark:hover:bg-white/5 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-neutral-100 dark:bg-white/5 border border-base flex items-center justify-center text-neutral-500">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-mono font-bold text-slate-600 dark:text-slate-300">{{ $s->jam_mulai }} - {{ $s->jam_selesai }}</span>
                                    <span class="text-[9px] text-neutral-400 uppercase font-black">WIB</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-bold text-neutral-800 dark:text-neutral-200 tracking-tight">{{ $s->mapel }}</p>
                            <p class="text-[9px] text-neutral-400 font-medium uppercase tracking-widest mt-0.5">Kurikulum Nasional</p>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-tighter border bg-blue-500/5 text-blue-500 border-blue-500/10">
                                {{ $s->kelas }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-xs font-bold text-neutral-600 dark:text-neutral-400">
                            {{ $s->hari }}
                        </td>
                        <td class="px-8 py-5 text-right">
                            <span class="inline-flex items-center gap-1 w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest ml-1">Aktif</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-3 opacity-20">
                                <i data-lucide="calendar-x" class="w-10 h-10"></i>
                                <p class="text-xs font-bold uppercase tracking-[0.2em]">Belum ada jadwal mengajar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Student & Class Management Section -->
    <div x-data="{ tab: '{{ $isWaliKelas ? 'perwalian' : 'kelas' }}' }" class="space-y-6">
        <div class="flex items-center justify-between border-b border-base">
            <div class="flex gap-8">
                @if($isWaliKelas)
                <button @click="tab = 'perwalian'" :class="tab === 'perwalian' ? 'border-b-2 border-neutral-900 dark:border-white text-neutral-900 dark:white' : 'text-neutral-400'" class="pb-4 text-[11px] font-black uppercase tracking-widest transition-all">
                    Siswa Perwalian (Kelas {{ $namaKelasWali }})
                </button>
                @endif
                <button @click="tab = 'kelas'" :class="tab === 'kelas' ? 'border-b-2 border-neutral-900 dark:border-white text-neutral-900 dark:white' : 'text-neutral-400'" class="pb-4 text-[11px] font-black uppercase tracking-widest transition-all">
                    Data Kelas
                </button>
                <button @click="tab = 'diajar'" :class="tab === 'diajar' ? 'border-b-2 border-neutral-900 dark:border-white text-neutral-900 dark:white' : 'text-neutral-400'" class="pb-4 text-[11px] font-black uppercase tracking-widest transition-all">
                    Nama Siswa
                </button>
            </div>
        </div>

        <!-- Perwalian Table -->
        @if($isWaliKelas)
        <div x-show="tab === 'perwalian'" class="card-pro overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-neutral-50/30 dark:bg-white/5 border-b border-base text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                            <th class="px-8 py-4">Profil Siswa</th>
                            <th class="px-8 py-4">NISN</th>
                            <th class="px-8 py-4">Jenis Kelamin</th>
                            <th class="px-8 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base">
                        @forelse($waliKelasSiswa as $siswa)
                        <tr class="hover:bg-neutral-50/30 dark:hover:bg-white/5 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-neutral-100 dark:bg-white/5 border border-base flex items-center justify-center overflow-hidden shrink-0">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}&background=f5f5f5&color=0a0a0a" class="w-full h-full grayscale">
                                    </div>
                                    <p class="text-sm font-bold text-neutral-800 dark:text-neutral-200 tracking-tight">{{ $siswa->nama }}</p>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-xs font-mono font-bold text-neutral-500">{{ $siswa->nisn }}</td>
                            <td class="px-8 py-5">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-tighter border {{ $siswa->jenis_kelamin == 'L' ? 'bg-blue-500/5 text-blue-500 border-blue-500/10' : 'bg-rose-500/5 text-rose-500 border-rose-500/10' }}">
                                    {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <button class="p-2 text-neutral-400 hover:text-neutral-900 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-white/5 rounded-lg transition-colors">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-10 text-center text-xs text-neutral-400 italic">Tidak ada siswa di kelas perwalian ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Data Kelas Tab -->
        <div x-show="tab === 'kelas'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($kelasSummary as $kelas)
            <div class="card-pro p-6 hover:border-neutral-900 dark:hover:border-white transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl bg-neutral-100 dark:bg-white/5 border border-base flex items-center justify-center text-neutral-500 group-hover:bg-neutral-900 group-hover:text-white dark:group-hover:bg-white dark:group-hover:text-neutral-900 transition-colors">
                        <i data-lucide="layout" class="w-6 h-6"></i>
                    </div>
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-tighter border bg-blue-500/5 text-blue-500 border-blue-500/10">
                        {{ $kelas['jumlah_siswa'] }} Siswa
                    </span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $kelas['nama_kelas'] }}</h3>
                <p class="text-xs text-neutral-500 mt-1">Kelas yang Anda ampu untuk mata pelajaran terkait.</p>
                <div class="mt-6 pt-6 border-t border-base flex justify-between items-center">
                    <button @click="tab = 'diajar'" class="text-[10px] font-black uppercase tracking-widest text-neutral-400 hover:text-neutral-900 dark:hover:text-white transition-colors">Lihat Detail Siswa</button>
                    <i data-lucide="arrow-right" class="w-4 h-4 text-neutral-300 group-hover:text-neutral-900 dark:group-hover:text-white transition-colors"></i>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center card-pro opacity-50">
                <p class="text-xs font-bold uppercase tracking-widest">Belum ada data kelas</p>
            </div>
            @endforelse
        </div>

        <!-- Diajar Table (Nama Siswa) -->
        <div x-show="tab === 'diajar'" class="card-pro overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-neutral-50/30 dark:bg-white/5 border-b border-base text-[10px] font-bold text-neutral-400 uppercase tracking-widest">
                            <th class="px-8 py-4">Profil Siswa</th>
                            <th class="px-8 py-4">Kelas</th>
                            <th class="px-8 py-4">NISN</th>
                            <th class="px-8 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base">
                        @forelse($studentsInClasses as $siswa)
                        <tr class="hover:bg-neutral-50/30 dark:hover:bg-white/5 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-neutral-100 dark:bg-white/5 border border-base flex items-center justify-center overflow-hidden shrink-0">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama) }}&background=f5f5f5&color=0a0a0a" class="w-full h-full grayscale">
                                    </div>
                                    <p class="text-sm font-bold text-neutral-800 dark:text-neutral-200 tracking-tight">{{ $siswa->nama }}</p>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-tighter border bg-neutral-500/5 text-neutral-500 border-neutral-500/10">
                                    {{ $siswa->kelas }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-xs font-mono font-bold text-neutral-500">{{ $siswa->nisn }}</td>
                            <td class="px-8 py-5 text-right">
                                <button class="p-2 text-neutral-400 hover:text-neutral-900 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-white/5 rounded-lg transition-colors">
                                    <i data-lucide="info" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-10 text-center text-xs text-neutral-400 italic">Tidak ada siswa di kelas yang Anda ajar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
