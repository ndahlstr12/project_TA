@extends('layouts.admin')

@section('title', 'Dashboard Wali Kelas')
@section('page_title', 'Overview Strategis Wali Kelas')

@section('content')
<div class="space-y-10">
    
    <!-- Unified Welcome Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-indigo-950 p-8 md:p-12 text-white shadow-2xl">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="max-w-xl">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-[0.2em] border border-white/10 text-indigo-200">Peran Ganda: Guru & Wali Kelas</span>
                </div>
                <h1 class="text-4xl font-black tracking-tighter leading-none mb-4">
                    Halo, <span class="text-amber-400">{{ explode(' ', Auth::user()->name)[0] }}</span>!
                </h1>
                <p class="text-indigo-100 text-sm font-medium opacity-80 leading-relaxed">
                    Kelola tugas mengajar pribadi Anda dan pantau kemajuan akademik kelas <span class="font-black text-white underline decoration-amber-400 underline-offset-4">{{ $kelas->nama_kelas ?? 'Belum Ditentukan' }}</span> dalam satu tampilan terpadu.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('walikelas.raport.index') }}" class="flex items-center justify-center gap-3 bg-white text-indigo-950 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-amber-400 transition-all duration-500 shadow-xl active:scale-95">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Manajemen Raport
                </a>
            </div>
        </div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2"></div>
    </div>

    <!-- Core Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card-pro p-6 flex items-center gap-5 group hover:border-indigo-500/50 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                <i data-lucide="users" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Peserta Didik</p>
                <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ count($siswas) }} Jiwa</p>
            </div>
        </div>

        <div class="card-pro p-6 flex items-center gap-5 group hover:border-emerald-500/50 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
                <i data-lucide="calendar" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Agenda Hari Ini</p>
                <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $jadwals->where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->count() }} Sesi</p>
            </div>
        </div>

        <div class="card-pro p-6 flex items-center gap-5 group hover:border-amber-500/50 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform">
                <i data-lucide="book-open" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Jurnal Kelas</p>
                <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">Siap</p>
            </div>
        </div>

        <div class="card-pro p-6 flex items-center gap-5 group hover:border-rose-500/50 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-500 group-hover:scale-110 transition-transform">
                <i data-lucide="award" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Analisis Ranking</p>
                <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">Update</p>
            </div>
        </div>
    </div>

    <!-- Dual Monitor Layout -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8" x-data="{ activeTab: 'perwalian' }">
        
        <!-- Main Panel -->
        <div class="xl:col-span-8 space-y-8">
            
            <!-- Navigation Tabs -->
            <div class="flex items-center justify-between px-2">
                <div class="flex bg-slate-100 dark:bg-white/5 p-1.5 rounded-2xl border border-base">
                    <button @click="activeTab = 'perwalian'" :class="activeTab === 'perwalian' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-400'" class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                        <i data-lucide="monitor" class="w-3.5 h-3.5"></i>
                        Pantau Kelas Perwalian
                    </button>
                    <button @click="activeTab = 'pribadi'" :class="activeTab === 'pribadi' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-400'" class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                        Tugas Mengajar Saya
                    </button>
                </div>
            </div>

            <!-- Content: Pantau Kelas Perwalian -->
            <div x-show="activeTab === 'perwalian'" x-transition class="space-y-6">
                <div class="flex items-center gap-4 px-2">
                    <div class="w-1 h-8 bg-amber-400 rounded-full"></div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Efikasi Penginputan Nilai</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Pantau progres guru mapel lain untuk kelas {{ $kelas->nama_kelas ?? '-' }}</p>
                    </div>
                </div>

                <div class="card-pro overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50 dark:bg-white/5 border-b border-base text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    <th class="px-8 py-4">Mata Pelajaran</th>
                                    <th class="px-8 py-4">Guru Pengampu</th>
                                    <th class="px-8 py-4">Progres Data</th>
                                    <th class="px-8 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-base">
                                @forelse($monitoringMapel as $monitor)
                                <tr class="hover:bg-slate-50/30 dark:hover:bg-white/5 transition-all group">
                                    <td class="px-8 py-5">
                                        <p class="text-xs font-black text-slate-800 dark:text-white tracking-tight">{{ $monitor['mapel'] }}</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">Kurikulum Merdeka</p>
                                    </td>
                                    <td class="px-8 py-5 text-xs font-bold text-slate-600 dark:text-slate-400">
                                        {{ $monitor['guru'] }}
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="w-full max-w-[120px] space-y-2">
                                            <div class="flex items-center justify-between text-[9px] font-black uppercase">
                                                <span class="text-slate-400">{{ $monitor['count'] }}/{{ $monitor['total'] }} Siswa</span>
                                                <span class="{{ $monitor['progres'] == 100 ? 'text-emerald-500' : 'text-amber-500' }}">{{ $monitor['progres'] }}%</span>
                                            </div>
                                            <div class="h-1.5 w-full bg-slate-100 dark:bg-white/10 rounded-full overflow-hidden">
                                                <div class="h-full {{ $monitor['progres'] == 100 ? 'bg-emerald-500' : 'bg-amber-500' }} rounded-full transition-all duration-1000" style="width: {{ $monitor['progres'] }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        @if($monitor['progres'] < 100)
                                        <button class="p-2 text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-xl transition-all" title="Kirim Pengingat">
                                            <i data-lucide="bell-ring" class="w-4 h-4"></i>
                                        </button>
                                        @else
                                        <span class="text-emerald-500 opacity-50"><i data-lucide="check-circle" class="w-4 h-4 ml-auto"></i></span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400 text-xs italic">Data jadwal tidak ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- DAFTAR SISWA KELAS PERWALIAN -->
                <div class="flex items-center gap-4 px-2 pt-6">
                    <div class="w-1 h-8 bg-blue-500 rounded-full"></div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Daftar Siswa Kelas Perwalian</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Total {{ count($siswas) }} Siswa Terdaftar di Kelas Anda</p>
                    </div>
                </div>

                <div class="card-pro overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50 dark:bg-white/5 border-b border-base text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    <th class="px-8 py-4">Nama Siswa</th>
                                    <th class="px-8 py-4">NISN</th>
                                    <th class="px-8 py-4">Jenis Kelamin</th>
                                    <th class="px-8 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-base">
                                @forelse($siswas as $siswa)
                                <tr class="hover:bg-slate-50/30 dark:hover:bg-white/5 transition-all group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center text-[10px] font-bold text-slate-400">
                                                {{ substr($siswa->nama, 0, 1) }}
                                            </div>
                                            <span class="text-xs font-bold text-slate-800 dark:text-white tracking-tight">{{ $siswa->nama }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-xs font-bold text-slate-500">
                                        {{ $siswa->nisn }}
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-white/5 text-[10px] font-bold text-slate-400 uppercase">
                                            {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <button class="p-2 text-slate-400 hover:text-indigo-500 transition-colors">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3 opacity-20">
                                            <i data-lucide="users-2" class="w-10 h-10"></i>
                                            <p class="text-[10px] font-black uppercase tracking-widest">Belum Ada Siswa di Kelas Ini</p>
                                            <p class="text-[9px] font-medium max-w-[200px] leading-relaxed">Admin perlu menetapkan siswa ke kelas ini melalui menu Manajemen Siswa.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Content: Tugas Mengajar Saya -->
            <div x-show="activeTab === 'pribadi'" x-transition class="space-y-6">
                <div class="flex items-center gap-4 px-2">
                    <div class="w-1 h-8 bg-indigo-500 rounded-full"></div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Jadwal & Progres Saya</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Kelola input nilai untuk kelas yang Anda ajar</p>
                    </div>
                </div>

                <div class="card-pro overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50 dark:bg-white/5 border-b border-base text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    <th class="px-8 py-4">Waktu</th>
                                    <th class="px-8 py-4">Mata Pelajaran</th>
                                    <th class="px-8 py-4">Kelas</th>
                                    <th class="px-8 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-base">
                                @forelse($jadwals as $j)
                                <tr class="hover:bg-slate-50/30 dark:hover:bg-white/5 transition-all group">
                                    <td class="px-8 py-5">
                                        <p class="text-xs font-black text-slate-800 dark:text-white">{{ $j->hari }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</p>
                                    </td>
                                    <td class="px-8 py-5 text-xs font-bold text-slate-700 dark:text-slate-300">
                                        {{ $j->mapel->nama_mapel ?? '-' }}
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="px-3 py-1 bg-indigo-50 dark:bg-white/5 rounded-md text-[9px] font-black text-indigo-500 uppercase border border-indigo-500/10">
                                            {{ $j->kelas->nama_kelas ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('shared.kehadiran.index', ['kelas_id' => $j->kelas_id]) }}" class="p-2 text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 rounded-lg transition-all" title="Input Absensi">
                                                <i data-lucide="user-check" class="w-4 h-4"></i>
                                            </a>
                                            <a href="{{ route('shared.nilai.index') }}" class="px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[10px] font-black uppercase tracking-widest rounded-lg hover:opacity-80 transition-all flex items-center gap-2">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                                Input Nilai
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400 text-xs italic">Belum ada jadwal mengajar.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Panel -->
        <div class="xl:col-span-4 space-y-8">
            <div class="card-pro p-8 border-l-4 border-l-amber-400">
                <div class="flex flex-col items-center text-center pb-6 border-b border-base">
                    <div class="w-20 h-20 rounded-[2rem] bg-amber-400/10 flex items-center justify-center text-amber-500 mb-6 group-hover:rotate-12 transition-transform">
                        <i data-lucide="shield-check" class="w-10 h-10"></i>
                    </div>
                    <h4 class="text-xl font-black text-slate-900 dark:text-white tracking-tighter">{{ $kelas->nama_kelas ?? 'Belum Ditentukan' }}</h4>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-2">{{ $kelas->jurusan ?? 'Semua Jurusan' }}</p>
                </div>
                
                <div class="py-8 space-y-6">
                    <div class="flex justify-between items-center text-[11px] font-bold">
                        <span class="text-slate-400 uppercase tracking-widest">Tahun Ajaran</span>
                        <span class="text-slate-900 dark:text-white">2026/2027</span>
                    </div>
                    <div class="flex justify-between items-center text-[11px] font-bold">
                        <span class="text-slate-400 uppercase tracking-widest">Tingkat Akademik</span>
                        <span class="text-slate-900 dark:text-white">{{ $kelas->tingkat ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[11px] font-bold">
                        <span class="text-slate-400 uppercase tracking-widest">Semester</span>
                        <span class="px-3 py-1 rounded-lg bg-amber-400/10 text-amber-600 font-black tracking-widest">GENAP</span>
                    </div>
                </div>

                <div class="space-y-3 pt-4 border-t border-base">
                    <a href="{{ route('walikelas.jurnal.index') }}" class="w-full flex items-center justify-center gap-3 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:scale-[1.02] transition-all">
                        <i data-lucide="notebook-pen" class="w-4 h-4"></i>
                        Input Jurnal Perilaku
                    </a>
                </div>
            </div>

            <!-- Quick Action Info -->
            <div class="card-pro p-8 bg-indigo-600 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h4 class="text-sm font-black uppercase tracking-widest mb-4">Pengingat Raport</h4>
                    <p class="text-xs text-indigo-100 font-medium leading-relaxed opacity-80">
                        Pastikan semua progres guru mapel di Tab Pemantauan mencapai 100% sebelum mencetak raport.
                    </p>
                    <div class="mt-6 flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest">Update Realtime</span>
                    </div>
                </div>
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
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

