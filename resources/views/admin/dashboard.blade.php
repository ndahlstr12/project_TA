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
            <button onclick="window.print()" class="px-5 py-2.5 bg-slate-800 text-white rounded-xl font-bold text-xs hover:bg-slate-900 transition-all shadow-lg flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4"></i>
                Cetak Laporan
            </button>
            <button class="px-5 py-2.5 bg-gold-500 text-white rounded-xl font-bold text-xs hover:bg-gold-600 transition-all shadow-lg flex items-center gap-2">
                <i data-lucide="download" class="w-4 h-4"></i>
                Ekspor Data
            </button>
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

    <!-- Analytical Surface -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Chart -->
        <div class="lg:col-span-8 card-pro p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-10 gap-4">
                <h3 class="text-sm font-extrabold uppercase tracking-widest text-navy-400">Grafik Performa Akademik</h3>
                <div class="flex bg-navy-50 dark:bg-white/5 p-1.5 rounded-xl">
                    <button class="px-4 py-1.5 text-[10px] font-bold bg-white dark:bg-navy-800 text-navy-900 dark:text-white rounded-lg shadow-sm">Bulanan</button>
                    <button class="px-4 py-1.5 text-[10px] font-bold text-navy-400 hover:text-navy-900 transition-colors">Tahunan</button>
                </div>
            </div>
            <div id="proChart" class="min-h-[350px]"></div>
        </div>

        <!-- Top Ranking Students (Merged from Analitik) -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            <div class="card-pro p-8 flex-1">
                <h3 class="text-sm font-extrabold uppercase tracking-widest text-navy-400 mb-6">Peringkat Tertinggi</h3>
                <div class="space-y-5">
                    @foreach([
                        ['Rendi Pratama', 'XII RPL 1', '92.4', '1'],
                        ['Ahmad Sholihin', 'XII RPL 1', '90.2', '2'],
                        ['Cici Mellyani', 'XI RPL 1', '89.5', '3'],
                        ['Dedi Kusuma', 'X RPL 1', '88.1', '4'],
                    ] as $rank)
                    <div class="flex items-center gap-4 group cursor-default">
                        <div class="w-8 h-8 rounded-lg bg-navy-50 dark:bg-white/5 flex items-center justify-center text-[10px] font-black text-navy-400 group-hover:bg-gold-500 group-hover:text-white transition-all">
                            {{ $rank[3] }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-navy-900 dark:text-white truncate">{{ $rank[0] }}</p>
                            <p class="text-[9px] text-navy-400 uppercase font-bold">{{ $rank[1] }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-black text-blue-600 dark:text-blue-400">{{ $rank[2] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button class="w-full mt-10 py-3 border border-dashed border-navy-100 dark:border-white/10 rounded-2xl text-[10px] font-bold uppercase tracking-widest text-navy-400 hover:text-navy-900 dark:hover:text-white transition-all">
                    Lihat Selengkapnya
                </button>
            </div>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-sm font-extrabold uppercase tracking-widest text-navy-400">Status Penginputan Nilai</h3>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <span class="text-[10px] font-bold text-navy-400 uppercase">Selesai</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-gold-500"></div>
                    <span class="text-[10px] font-bold text-navy-400 uppercase">Proses</span>
                </div>
            </div>
        </div>

        <div class="card-pro overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-navy-50/50 dark:bg-white/5 border-b border-navy-100 dark:border-white/5">
                            <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest">Nama Siswa</th>
                            <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest text-center">Kelas</th>
                            <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest text-center">Semester</th>
                            <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest text-right">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-navy-100 dark:divide-white/5">
                        @foreach([
                            ['Ahmad Sholihin', 'XII RPL 1', 'Ganjil', 'Selesai', 'bg-emerald-500', '94.2'],
                            ['Budi Pratama', 'XII TKJ 2', 'Ganjil', 'Proses', 'bg-gold-500', '82.5'],
                            ['Cici Mellyani', 'XI RPL 1', 'Ganjil', 'Selesai', 'bg-emerald-500', '89.5'],
                            ['Dedi Kusuma', 'X RPL 1', 'Ganjil', 'Belum', 'bg-navy-100', '0.0'],
                        ] as $row)
                        <tr class="hover:bg-navy-50/30 dark:hover:bg-white/5 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-9 h-9 rounded-xl bg-navy-800 text-white flex items-center justify-center text-xs font-bold shadow-lg shadow-navy-800/20 group-hover:scale-110 transition-transform">
                                        {{ substr($row[0], 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-navy-900 dark:text-white">{{ $row[0] }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="text-xs font-bold text-navy-500 dark:text-navy-400">{{ $row[1] }}</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="text-[10px] font-extrabold text-navy-300 dark:text-navy-600 uppercase tracking-widest">{{ $row[2] }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center">
                                    <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-navy-50 dark:bg-white/5 border border-navy-100 dark:border-white/10">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $row[4] }}"></div>
                                        <span class="text-[10px] font-bold text-navy-600 dark:text-navy-400 uppercase">{{ $row[3] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <span class="text-sm font-bold {{ $row[5] > 0 ? 'text-gold-600' : 'text-navy-200' }}">{{ $row[5] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-6 bg-navy-50/30 dark:bg-white/5 border-t border-navy-100 dark:border-white/5 flex items-center justify-center">
                <a href="#" class="text-[10px] font-bold text-navy-400 uppercase tracking-widest hover:text-gold-600 transition-colors">Lihat Semua Data Siswa</a>
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
