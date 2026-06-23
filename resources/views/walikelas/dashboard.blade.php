@extends('layouts.admin')

@section('title', 'Beranda Wali Kelas')
@section('page_title', 'Dashboard Wali Kelas')

@section('content')
<div class="space-y-4 md:space-y-8">
    
    <!-- Header Selamat Datang -->
    <div class="relative overflow-hidden rounded-2xl md:rounded-[2.5rem] bg-indigo-900 p-5 md:p-12 text-white shadow-2xl">
        <div class="relative z-10 flex flex-col gap-5 md:flex-row md:items-center md:justify-between md:gap-8">
            <div class="max-w-2xl">
                <div class="flex items-center gap-2 mb-3 flex-wrap">
                    <span class="px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-[8px] md:text-[9px] font-black uppercase tracking-widest border border-white/10 text-indigo-100">Wali Kelas: {{ $kelas->nama_kelas ?? '-' }}</span>
                </div>
                <h1 class="text-xl md:text-5xl font-black tracking-tighter leading-[1.1] mb-2 md:mb-4">
                    Halo, <span class="text-amber-400">{{ explode(' ', Auth::user()->name)[0] }}</span>!
                </h1>
                <p class="text-indigo-100 text-xs md:text-base font-medium opacity-80 leading-relaxed">
                    {{ $semester }} | {{ $tahunAjaran }}. Pantau kemajuan akademik siswa Anda.
                </p>
            </div>
            <div class="w-full md:w-auto">
                <a href="{{ route('walikelas.raport.index') }}" class="w-full md:w-auto flex items-center justify-center gap-2 bg-white text-indigo-900 px-5 py-3 rounded-xl font-black text-[10px] md:text-xs uppercase tracking-widest hover:bg-amber-400 transition-all duration-300 shadow-xl active:scale-95">
                    <i data-lucide="file-text" class="w-4 h-4 shrink-0"></i>
                    Manajemen Raport
                </a>
            </div>
        </div>
        <div class="absolute top-0 right-0 w-48 h-48 md:w-96 md:h-96 bg-indigo-500/20 rounded-full blur-[60px] md:blur-[120px] -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-8">
        <!-- Total Siswa -->
        <div class="card-pro p-4 md:p-8 flex items-center gap-3 md:gap-4 bg-white dark:bg-slate-900">
            <div class="w-10 h-10 md:w-16 md:h-16 rounded-xl md:rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 shrink-0">
                <i data-lucide="users" class="w-5 h-5 md:w-8 md:h-8"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest truncate">Total Siswa</p>
                <p class="text-lg md:text-3xl font-black text-slate-900 dark:text-white leading-none mt-0.5">{{ $stats['total_siswa'] }}</p>
            </div>
        </div>

        <!-- Kehadiran Hari Ini -->
        @php $persenHadir = $stats['total_siswa'] > 0 ? round(($stats['hadir_today'] / $stats['total_siswa']) * 100) : 0; @endphp
        <div class="card-pro p-4 md:p-8 flex items-center gap-3 md:gap-4 bg-white dark:bg-slate-900">
            <div class="w-10 h-10 md:w-16 md:h-16 rounded-xl md:rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 shrink-0">
                <i data-lucide="user-check" class="w-5 h-5 md:w-8 md:h-8"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest truncate">Hadir Hari Ini</p>
                <p class="text-lg md:text-3xl font-black text-slate-900 dark:text-white leading-none mt-0.5">{{ $persenHadir }}%</p>
            </div>
        </div>

        <!-- Progres Raport -->
        <div class="card-pro p-4 md:p-8 flex items-center gap-3 md:gap-4 bg-white dark:bg-slate-900">
            <div class="w-10 h-10 md:w-16 md:h-16 rounded-xl md:rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500 shrink-0">
                <i data-lucide="file-check" class="w-5 h-5 md:w-8 md:h-8"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest truncate">Raport</p>
                <p class="text-lg md:text-3xl font-black text-slate-900 dark:text-white leading-none mt-0.5">{{ $stats['raport_selesai'] }}/{{ $stats['total_siswa'] }}</p>
            </div>
        </div>

        <!-- Siswa Bermasalah -->
        <div class="card-pro p-4 md:p-8 flex items-center gap-3 md:gap-4 bg-white dark:bg-slate-900">
            <div class="w-10 h-10 md:w-16 md:h-16 rounded-xl md:rounded-2xl bg-rose-500/10 flex items-center justify-center text-rose-500 shrink-0">
                <i data-lucide="alert-triangle" class="w-5 h-5 md:w-8 md:h-8"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest truncate">Alpa > 3x</p>
                <p class="text-lg md:text-3xl font-black text-slate-900 dark:text-white leading-none mt-0.5">{{ $stats['perlu_perhatian']->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Layout Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 md:gap-8" x-data="{ activeTab: 'perwalian' }">
        
        <!-- Panel Utama -->
        <div class="lg:col-span-8 space-y-4 md:space-y-6">
            
            <!-- Tab Navigasi -->
            <div class="flex bg-slate-100 dark:bg-white/5 p-1 rounded-xl border border-base w-full">
                <button @click="activeTab = 'perwalian'" :class="activeTab === 'perwalian' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-400'" class="flex-1 px-4 py-2 text-[9px] md:text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">
                    Pantauan
                </button>
                <button @click="activeTab = 'jadwal'" :class="activeTab === 'jadwal' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-400'" class="flex-1 px-4 py-2 text-[9px] md:text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">
                    Jadwal
                </button>
            </div>

            <!-- Konten Tab: Pantauan Kelas -->
            <div x-show="activeTab === 'perwalian'" x-transition class="space-y-4 md:space-y-6">
                <!-- Grafik -->
                <div class="card-pro p-4 md:p-8 bg-white dark:bg-slate-900">
                    <h4 class="text-[10px] md:text-xs font-black uppercase tracking-widest text-slate-800 dark:text-white mb-4 md:mb-6 flex items-center gap-2">
                        <i data-lucide="trending-up" class="w-4 h-4 text-indigo-500 shrink-0"></i>
                        Grafik Nilai Rata-rata Mapel
                    </h4>
                    <div class="relative h-[200px] md:h-[350px] w-full">
                        <canvas id="chartNilaiMapel"></canvas>
                    </div>
                </div>

                <!-- Progres Nilai Guru Mapel -->
                <div class="card-pro p-4 md:p-8 bg-white dark:bg-slate-900">
                    <h4 class="text-[10px] md:text-sm font-black uppercase tracking-widest text-slate-800 dark:text-white mb-4 md:mb-8 flex items-center gap-2">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-indigo-500 shrink-0"></i>
                        Progres Nilai Guru Mapel
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-x-12 md:gap-y-8">
                        @forelse($monitoringMapel as $monitor)
                        <div class="space-y-2 md:space-y-3">
                            <div class="flex justify-between items-start gap-2">
                                <span class="text-[11px] md:text-xs font-bold text-slate-700 dark:text-slate-200 leading-snug">{{ $monitor['mapel'] }}</span>
                                <span class="text-[11px] md:text-xs font-black shrink-0 {{ $monitor['progres'] == 100 ? 'text-emerald-500' : 'text-indigo-500' }}">{{ $monitor['progres'] }}%</span>
                            </div>
                            <div class="h-2 w-full bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full {{ $monitor['progres'] == 100 ? 'bg-emerald-500' : 'bg-indigo-500' }} rounded-full transition-all duration-1000" style="width: {{ $monitor['progres'] }}%"></div>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-[9px] text-slate-400 font-black uppercase tracking-tighter truncate max-w-[60%]">{{ $monitor['guru'] }}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest shrink-0">{{ $monitor['count'] }}/{{ $monitor['total'] }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full py-8 text-center opacity-30">
                            <i data-lucide="layers" class="w-10 h-10 mx-auto mb-3"></i>
                            <p class="text-xs font-bold uppercase tracking-widest">Belum ada data nilai</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Siswa Perlu Perhatian -->
                @if($stats['perlu_perhatian']->count() > 0)
                <div class="card-pro p-4 md:p-6 border-l-4 border-rose-500 bg-rose-500/5">
                    <h4 class="text-[10px] md:text-sm font-black uppercase tracking-widest text-rose-600 mb-3 md:mb-4 flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                        Perlu Perhatian (Alpa > 3x)
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 md:gap-3">
                        @foreach($stats['perlu_perhatian'] as $siswa)
                        <div class="flex items-center gap-3 p-3 bg-white dark:bg-slate-800 rounded-xl border border-rose-100 dark:border-rose-500/20 shadow-sm">
                            <div class="w-8 h-8 rounded-full bg-rose-500/10 flex items-center justify-center text-rose-500 text-[10px] font-bold shrink-0">
                                {{ substr($siswa->nama, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ $siswa->nama }}</p>
                                <p class="text-[9px] text-rose-500 font-bold uppercase">{{ $siswa->raports->first()->alpa ?? 0 }} Alpa</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Konten Tab: Jadwal Mengajar -->
            <div x-show="activeTab === 'jadwal'" x-transition class="space-y-3">
                @forelse($jadwals as $j)
                <div class="card-pro p-3 md:p-5 bg-white dark:bg-slate-900 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="flex flex-col items-center justify-center w-12 h-12 md:w-16 md:h-16 bg-slate-50 dark:bg-white/5 rounded-xl border border-base shrink-0">
                            <span class="text-[8px] md:text-[10px] font-black text-indigo-500 uppercase">{{ substr($j->hari, 0, 3) }}</span>
                            <span class="text-[10px] md:text-xs font-black text-slate-400">{{ $j->jam_mulai }}</span>
                        </div>
                        <div class="min-w-0">
                            <h5 class="text-xs md:text-sm font-black text-slate-800 dark:text-white leading-tight truncate">{{ $j->mapel->nama_mapel ?? '-' }}</h5>
                            <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">{{ $j->kelas->nama_kelas ?? '-' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('shared.kehadiran.index', ['jadwal_id' => $j->id]) }}" class="shrink-0 flex items-center gap-1.5 px-3 py-2 bg-emerald-500/10 text-emerald-600 rounded-xl text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                        Presensi
                    </a>
                </div>
                @empty
                <div class="p-10 text-center opacity-30">
                    <i data-lucide="calendar-off" class="w-10 h-10 mx-auto mb-2"></i>
                    <p class="text-[10px] font-bold uppercase">Kosong</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-4 md:space-y-6">
            <!-- Progres Raport -->
            <div class="card-pro p-5 md:p-6 bg-white dark:bg-slate-900">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-800 dark:text-white mb-4 md:mb-6">Penyelesaian Raport</h4>
                <div class="flex flex-col items-center justify-center py-2">
                    <div class="relative w-28 h-28 flex items-center justify-center">
                        @php $persenRaport = $stats['total_siswa'] > 0 ? round(($stats['raport_selesai'] / $stats['total_siswa']) * 100) : 0; @endphp
                        <svg class="w-full h-full transform -rotate-90">
                            <circle cx="56" cy="56" r="50" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-100 dark:text-white/5" />
                            <circle cx="56" cy="56" r="50" stroke="currentColor" stroke-width="8" fill="transparent" stroke-dasharray="314.16" stroke-dashoffset="{{ 314.16 - (314.16 * $persenRaport / 100) }}" class="text-indigo-500 transition-all duration-1000" />
                        </svg>
                        <span class="absolute text-xl font-black text-slate-900 dark:text-white">{{ $persenRaport }}%</span>
                    </div>
                </div>
            </div>

            <div class="card-pro p-5 md:p-6 bg-white dark:bg-slate-900 border-t-4 border-t-amber-400">
                <a href="{{ route('walikelas.jurnal.index') }}" class="w-full flex items-center justify-center gap-2 py-3 md:py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg">
                    <i data-lucide="notebook-pen" class="w-4 h-4 shrink-0"></i>
                    Input Jurnal Perilaku
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const ctx = document.getElementById('chartNilaiMapel');
        if (ctx) {
            const dataMapel = @json($monitoringMapel->values());
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dataMapel.map(i => i.mapel),
                    datasets: [{
                        label: 'Rata-rata Nilai',
                        data: dataMapel.map(i => i.avg_nilai),
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        borderColor: 'rgb(79, 70, 229)',
                        borderWidth: 2,
                        borderRadius: 8,
                        barThickness: 24
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, max: 100, grid: { color: 'rgba(0,0,0,0.05)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });
</script>
@endpush