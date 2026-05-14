@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Utama')

@section('content')
<div class="space-y-10">
    
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-merri font-bold text-navy-900 dark:text-white">Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}!</h1>
            <p class="text-navy-400 font-medium mt-1">Berikut adalah ringkasan performa akademik dan aktivitas hari ini.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.dashboard.export-pdf') }}" class="px-5 py-2.5 bg-slate-800 text-white rounded-xl font-bold text-xs hover:bg-slate-900 transition-all shadow-lg flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                Ekspor PDF
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach([
            ['Total Siswa', $stats['total_siswa'], '+2.4%', 'users', 'bg-blue-500'],
            ['Rata-rata Nilai', $stats['rata_rata_nilai'], '+1.2', 'graduation-cap', 'bg-indigo-500'],
            ['Tingkat Kehadiran', $stats['kehadiran_rata'], '+0.5%', 'calendar-check', 'bg-emerald-500'],
            ['Kriteria SPK', $stats['total_kriteria'], 'Stabil', 'sparkles', 'bg-gold-500']
        ] as $stat)
        <div class="card-pro p-6 relative overflow-hidden group hover:border-gold-300 transition-all">
            <div class="flex items-center justify-between relative z-10">
                <div class="w-12 h-12 {{ $stat[4] }} rounded-2xl flex items-center justify-center text-white shadow-lg shadow-{{ explode('-', $stat[4])[1] }}-500/20 group-hover:scale-110 transition-transform">
                    <i data-lucide="{{ $stat[3] }}" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-lg">
                    {{ $stat[2] }}
                </span>
            </div>
            <div class="mt-5 relative z-10">
                <span class="text-[10px] font-extrabold text-navy-300 uppercase tracking-widest">{{ $stat[0] }}</span>
                <h3 class="text-2xl font-bold text-navy-900 dark:text-white mt-1">{{ $stat[1] }}</h3>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-navy-50 dark:bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        </div>
        @endforeach
    </div>

    <!-- Teacher Monitoring Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Monitoring Wali Kelas -->
        <div class="card-pro p-8 border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-extrabold uppercase tracking-widest text-navy-400">Progres Wali Kelas</h3>
                    <p class="text-[10px] text-navy-300 font-bold uppercase mt-1">Pengisian Raport Semester</p>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-black text-navy-900 dark:text-white">{{ $stats['walikelas_selesai'] }}/{{ $stats['walikelas_total'] }}</span>
                </div>
            </div>
            @php
                $waliPercent = $stats['walikelas_total'] > 0 ? ($stats['walikelas_selesai'] / $stats['walikelas_total']) * 100 : 0;
            @endphp
            <div class="space-y-3">
                <div class="w-full bg-navy-50 dark:bg-white/5 h-2.5 rounded-full overflow-hidden">
                    <div class="bg-blue-500 h-full rounded-full transition-all duration-1000" style="width: {{ $waliPercent }}%"></div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold text-navy-400 uppercase">Tingkat Penyelesaian</span>
                    <span class="text-[10px] font-black text-blue-500">{{ round($waliPercent) }}%</span>
                </div>
            </div>
        </div>

        <!-- Monitoring Guru Mapel -->
        <div class="card-pro p-8 border-l-4 border-l-gold-500">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-extrabold uppercase tracking-widest text-navy-400">Progres Guru Mapel</h3>
                    <p class="text-[10px] text-navy-300 font-bold uppercase mt-1">Upload Nilai Mata Pelajaran</p>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-black text-navy-900 dark:text-white">{{ $stats['guru_mapel_selesai'] }}/{{ $stats['guru_mapel_total'] }}</span>
                </div>
            </div>
            @php
                $guruPercent = $stats['guru_mapel_total'] > 0 ? ($stats['guru_mapel_selesai'] / $stats['guru_mapel_total']) * 100 : 0;
            @endphp
            <div class="space-y-3">
                <div class="w-full bg-navy-50 dark:bg-white/5 h-2.5 rounded-full overflow-hidden">
                    <div class="bg-gold-500 h-full rounded-full transition-all duration-1000" style="width: {{ $guruPercent }}%"></div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold text-navy-400 uppercase">Tingkat Penyelesaian</span>
                    <span class="text-[10px] font-black text-gold-500">{{ round($guruPercent) }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Teacher Status Table Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-sm font-extrabold uppercase tracking-widest text-navy-400">Daftar Detail Progres Guru</h3>
            <a href="#" class="text-[10px] font-bold text-blue-500 uppercase tracking-widest hover:underline">Lihat Semua Guru</a>
        </div>

        <div class="card-pro overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-navy-50/50 dark:bg-white/5 border-b border-navy-100 dark:border-white/5">
                            <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest">Nama Tenaga Pendidik</th>
                            <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest text-center">Peran</th>
                            <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest text-center">Jenis Tugas</th>
                            <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-navy-100 dark:divide-white/5">
                        @foreach($pendingTeachers as $teacher)
                        <tr class="hover:bg-navy-50/30 dark:hover:bg-white/5 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-white/5 text-navy-900 dark:text-white flex items-center justify-center text-xs font-bold border border-navy-100 dark:border-white/10 group-hover:scale-110 transition-transform">
                                        {{ substr($teacher->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-navy-900 dark:text-white">{{ $teacher->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-2 py-1 bg-navy-50 dark:bg-white/5 text-[9px] font-black text-navy-400 uppercase rounded-lg border border-navy-100 dark:border-white/10">
                                    {{ $teacher->role }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="text-xs font-bold text-navy-500 dark:text-navy-400">{{ $teacher->tipe_tugas }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center">
                                    @if($teacher->status_tugas === 'Selesai')
                                    <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-500 uppercase">Selesai</span>
                                    </div>
                                    @else
                                    <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20">
                                        <div class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></div>
                                        <span class="text-[10px] font-bold text-rose-600 dark:text-rose-500 uppercase">Tertunda</span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                @if($teacher->status_tugas !== 'Selesai')
                                <button class="p-2 text-navy-400 hover:text-blue-500 transition-colors" title="Kirim Pengingat">
                                    <i data-lucide="bell-ring" class="w-4 h-4"></i>
                                </button>
                                @else
                                <button class="p-2 text-emerald-500 opacity-50 cursor-not-allowed" title="Sudah Selesai">
                                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Analytical Surface -->
    <div class="grid grid-cols-1 gap-8">
        <!-- Main Chart -->
        <div class="card-pro p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-10 gap-4">
                <h3 class="text-sm font-extrabold uppercase tracking-widest text-navy-400">Grafik Performa Akademik</h3>
                <div class="flex bg-navy-50 dark:bg-white/5 p-1.5 rounded-xl">
                    <button class="px-4 py-1.5 text-[10px] font-bold bg-white dark:bg-navy-800 text-navy-900 dark:text-white rounded-lg shadow-sm">Bulanan</button>
                    <button class="px-4 py-1.5 text-[10px] font-bold text-navy-400 hover:text-navy-900 transition-colors">Tahunan</button>
                </div>
            </div>
            <div id="proChart" class="min-h-[350px]"></div>
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
                sparkline: { enabled: false }
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
                borderColor: localStorage.getItem('darkMode') === 'true' ? '#1a2c5b' : '#f4f6fa',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            xaxis: {
                categories: ['X-1', 'X-2', 'XI-1', 'XI-2', 'XII-1', 'XII-2', 'XII-3'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#a8b6cc', fontSize: '10px', fontWeight: 700 } }
            },
            yaxis: {
                labels: { style: { colors: '#a8b6cc', fontSize: '10px', fontWeight: 700 } }
            },
            tooltip: {
                theme: 'dark',
                x: { show: true }
            }
        };

        const chart = new ApexCharts(document.querySelector("#proChart"), options);
        chart.render();
    });
</script>
@endpush
