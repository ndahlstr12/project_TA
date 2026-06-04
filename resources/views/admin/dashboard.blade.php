@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Utama')

@section('content')
<div class="space-y-12">
    
    <!-- Hero Welcome Section -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-navy-950 p-8 md:p-12 text-white shadow-2xl shadow-navy-950/20">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="max-w-xl">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-[0.2em] border border-white/10">Sistem Manajemen Terpadu</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-black tracking-tighter leading-none mb-4">
                    Halo, <span class="text-gold-400">{{ explode(' ', Auth::user()->name)[0] }}</span>!
                </h1>
                <p class="text-navy-100 text-sm md:text-base font-medium opacity-80 leading-relaxed">
                    Pantau efisiensi operasional dan perkembangan akademik sekolah secara real-time. Semua kontrol ada dalam genggaman Anda.
                </p>
            </div>
            <div class="shrink-0 flex gap-4">
                <a href="{{ route('admin.dashboard.export-pdf') }}" class="group flex items-center gap-3 bg-white text-navy-950 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gold-400 transition-all duration-500 shadow-xl active:scale-95">
                    <i data-lucide="download-cloud" class="w-4 h-4 group-hover:bounce"></i>
                    Unduh Laporan PDF
                </a>
            </div>
        </div>
        
        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-gold-400/10 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/10 rounded-full blur-[80px] translate-y-1/2 -translate-x-1/2"></div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stats_data = [
                ['Siswa Terdaftar', $stats['total_siswa'], 'users', 'bg-blue-500', 'blue'],
                ['Rata-rata Nilai', $stats['rata_rata_nilai'], 'award', 'bg-purple-500', 'purple'],
                ['Presensi Harian', $stats['kehadiran_rata'], 'check-square', 'bg-emerald-500', 'emerald'],
                ['Variabel SPK', $stats['total_kriteria'], 'pie-chart', 'bg-orange-500', 'orange']
            ];
        @endphp

        @foreach($stats_data as $stat)
        <div class="group relative card-pro p-1 hover:border-{{ $stat[4] }}-500/50 transition-all duration-500 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="w-14 h-14 rounded-2xl {{ $stat[3] }} text-white flex items-center justify-center shadow-2xl shadow-{{ $stat[4] }}-500/20 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <i data-lucide="{{ $stat[2] }}" class="w-7 h-7"></i>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] font-black text-neutral-400 uppercase tracking-widest">{{ $stat[0] }}</span>
                        <h3 class="text-3xl font-black text-neutral-900 dark:text-white mt-1 tracking-tighter">{{ $stat[1] }}</h3>
                    </div>
                </div>
                <div class="mt-8 flex items-center gap-2">
                    <div class="flex -space-x-2">
                        @for($i=1; $i<=3; $i++)
                        <div class="w-6 h-6 rounded-full border-2 border-white dark:border-navy-900 bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center text-[8px] font-bold">
                            {{ $i }}
                        </div>
                        @endfor
                    </div>
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-tighter">Diperbarui Baru Saja</span>
                </div>
            </div>
            <!-- Background Decoration -->
            <div class="absolute -right-12 -bottom-12 w-32 h-32 {{ $stat[3] }} opacity-[0.03] rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        </div>
        @endforeach
    </div>

    <!-- Operations & Progress Tracking -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Monitoring Central -->
        <div class="lg:col-span-8 space-y-8">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-neutral-100 dark:bg-white/5 flex items-center justify-center text-neutral-400 border border-base">
                        <i data-lucide="bar-chart-3" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-neutral-900 dark:text-white tracking-tight">Efikasi Kurikulum</h3>
                        <p class="text-[10px] text-neutral-400 font-bold uppercase tracking-widest mt-0.5">Analisis Sebaran Nilai Per-Kelas</p>
                    </div>
                </div>
                <div class="flex bg-neutral-100 dark:bg-white/5 p-1 rounded-xl">
                    <button class="px-4 py-2 text-[10px] font-black bg-white dark:bg-navy-800 text-neutral-900 dark:text-white rounded-lg shadow-sm uppercase tracking-widest">Semester Ganjil</button>
                    <button class="px-4 py-2 text-[10px] font-black text-neutral-400 hover:text-neutral-900 uppercase tracking-widest transition-all">Genap</button>
                </div>
            </div>

            <div class="card-pro p-8">
                <div id="proChart" class="min-h-[350px]"></div>
            </div>

            <!-- Activity Stream -->
            <div class="space-y-6 pt-4">
                <div class="flex items-center justify-between px-2">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-neutral-400">Log Aktivitas Terbaru</h3>
                    <button class="text-[10px] font-bold text-blue-500 uppercase tracking-widest hover:underline transition-all">Refresh Aliran</button>
                </div>

                <div class="card-pro overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-neutral-50/50 dark:bg-white/5 border-b border-base text-[9px] font-black text-neutral-400 dark:text-slate-400 uppercase tracking-[0.2em]">
                                    <th class="px-8 py-4">Entitas</th>
                                    <th class="px-8 py-4">Spesialisasi</th>
                                    <th class="px-8 py-4">Jenis Tugas</th>
                                    <th class="px-8 py-4 text-right">Protokol Status</th>
                                </tr>
                            </thead>                            <tbody class="divide-y divide-base">
                                @foreach($pendingTeachers as $teacher)
                                <tr class="hover:bg-neutral-50/30 dark:hover:bg-white/5 transition-all group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-neutral-100 dark:bg-white/5 border border-base flex items-center justify-center overflow-hidden grayscale group-hover:grayscale-0 transition-all duration-500">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=random" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-neutral-800 dark:text-neutral-200 tracking-tight">{{ $teacher->name }}</p>
                                                <p class="text-[9px] text-neutral-400 font-bold uppercase mt-1">ID: {{ $teacher->guru_id ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="px-3 py-1 bg-neutral-50 dark:bg-white/5 rounded-md text-[9px] font-black text-neutral-400 uppercase border border-base tracking-tighter">
                                            {{ $teacher->role }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-accent/20 border border-accent/40"></div>
                                            <span class="text-[10px] font-bold text-neutral-600 dark:text-neutral-400 uppercase">{{ $teacher->tipe_tugas }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        @if($teacher->status_tugas === 'Selesai')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/5 text-emerald-500 text-[9px] font-black uppercase tracking-widest border border-emerald-500/10">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i> Tuntas
                                        </span>
                                        @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-500/5 text-orange-500 text-[9px] font-black uppercase tracking-widest border border-orange-500/10">
                                            <i data-lucide="clock" class="w-3 h-3 animate-pulse"></i> Pending
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebars -->
        <div class="lg:col-span-4 space-y-8">
            <div class="card-pro p-8 bg-neutral-900 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gold-400 mb-6">Distribusi Tugas</h3>
                    <div class="space-y-8">
                        @php
                            $waliPercent = $stats['walikelas_total'] > 0 ? ($stats['walikelas_selesai'] / $stats['walikelas_total']) * 100 : 0;
                            $guruPercent = $stats['guru_mapel_total'] > 0 ? ($stats['guru_mapel_selesai'] / $stats['guru_mapel_total']) * 100 : 0;
                        @endphp
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">Wali Kelas</span>
                                <span class="text-[10px] font-black text-white uppercase">{{ round($waliPercent) }}%</span>
                            </div>
                            <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full transition-all duration-1000" style="width: {{ $waliPercent }}%"></div>
                            </div>
                            <p class="text-[9px] text-neutral-500 font-bold uppercase">{{ $stats['walikelas_selesai'] }} dari {{ $stats['walikelas_total'] }} pengampu selesai</p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">Guru Mata Pelajaran</span>
                                <span class="text-[10px] font-black text-white uppercase">{{ round($guruPercent) }}%</span>
                            </div>
                            <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-gold-400 rounded-full transition-all duration-1000" style="width: {{ $guruPercent }}%"></div>
                            </div>
                            <p class="text-[9px] text-neutral-500 font-bold uppercase">{{ $stats['guru_mapel_selesai'] }} dari {{ $stats['guru_mapel_total'] }} pengampu selesai</p>
                        </div>
                    </div>
                    
                    <button class="w-full mt-10 py-4 bg-white/5 hover:bg-white/10 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] border border-white/10 transition-all active:scale-95">
                        Kirim Notifikasi Masal
                    </button>
                </div>
                <!-- Decoration -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-gold-400/5 rounded-full blur-2xl"></div>
            </div>

            <!-- Mini Calendar or Info Card -->
            <div class="card-pro p-8 border-dashed">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center">
                        <i data-lucide="calendar" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-neutral-900 dark:text-white tracking-tighter">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</h4>
                        <p class="text-[10px] text-neutral-400 font-bold uppercase tracking-widest">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="p-4 bg-neutral-50 dark:bg-white/5 rounded-2xl border border-base">
                        <p class="text-[9px] font-black text-neutral-400 uppercase tracking-widest mb-1">Mendatang</p>
                        <p class="text-xs font-bold text-neutral-800 dark:text-neutral-200">Rapat Pleno Kenaikan Kelas</p>
                        <p class="text-[9px] text-accent font-bold uppercase mt-2">Besok • 09:00 WIB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const options = {
            series: [{
                name: 'Rata-rata Nilai',
                data: [78, 82, 85, 80, 88, 92, 84]
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                sparkline: { enabled: false },
                background: 'transparent'
            },
            colors: ['#e8a020'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100, 100]
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            dataLabels: { enabled: false },
            grid: {
                borderColor: localStorage.getItem('darkMode') === 'true' ? 'rgba(255,255,255,0.05)' : '#f4f6fa',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            xaxis: {
                categories: ['X-1', 'X-2', 'XI-1', 'XI-2', 'XII-1', 'XII-2', 'XII-3'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#a8b6cc', fontSize: '9px', fontWeight: 700 } }
            },
            yaxis: {
                labels: { style: { colors: '#a8b6cc', fontSize: '9px', fontWeight: 700 } }
            },
            tooltip: {
                theme: 'dark',
                x: { show: true }
            }
        };

        const chart = new ApexCharts(document.querySelector("#proChart"), options);
        chart.render();
        
        // Refresh icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush

