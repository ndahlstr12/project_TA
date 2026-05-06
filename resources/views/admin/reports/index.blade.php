@extends('layouts.admin')

@section('title', 'Laporan Sekolah')
@section('page_title', 'Pusat Data & Pesan')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Laporan & Statistik</h2>
            <p class="text-slate-500 font-medium">Rekapitulasi performa akademik SMKN 1 Sungailiat.</p>
        </div>
        <button onclick="window.print()" class="inline-flex items-center justify-center space-x-2 bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-6 rounded-2xl shadow-lg transition-all active:scale-95">
            <i class="fas fa-print"></i>
            <span>Cetak Laporan</span>
        </button>
    </div>

    <!-- Main Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Rata-rata Nilai Siswa</p>
                <h3 class="text-4xl font-black text-indigo-600 tracking-tighter">{{ $stats['rata_rata_nilai'] }}</h3>
                <div class="mt-4 flex items-center text-xs font-bold text-emerald-500">
                    <i class="fas fa-arrow-up mr-1"></i> +2.4% Semester Ini
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 text-8xl text-indigo-50 opacity-50 group-hover:scale-110 transition duration-500">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tingkat Kehadiran</p>
                <h3 class="text-4xl font-black text-emerald-600 tracking-tighter">{{ $stats['kehadiran_rata'] }}</h3>
                <div class="mt-4 flex items-center text-xs font-bold text-emerald-500">
                    <i class="fas fa-check-circle mr-1"></i> Kehadiran Sangat Baik
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 text-8xl text-emerald-50 opacity-50 group-hover:scale-110 transition duration-500">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Entri Nilai</p>
                <h3 class="text-4xl font-black text-orange-600 tracking-tighter">15.420</h3>
                <div class="mt-4 flex items-center text-xs font-bold text-slate-400 italic">
                    Semua mapel telah terisi
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 text-8xl text-orange-50 opacity-50 group-hover:scale-110 transition duration-500">
                <i class="fas fa-file-invoice"></i>
            </div>
        </div>
    </div>

    <!-- Secondary Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Rankings Summary -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-lg text-slate-800">Peringkat Tertinggi (Siswa)</h3>
                <span class="text-[10px] font-extrabold bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full uppercase tracking-widest">TOP 5</span>
            </div>
            <div class="p-4">
                <div class="space-y-2">
                    <!-- Card-style list item -->
                    <div class="flex items-center p-4 hover:bg-slate-50 rounded-2xl transition">
                        <div class="w-10 h-10 bg-indigo-500 text-white rounded-xl flex items-center justify-center font-bold mr-4">1</div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-700">Rendi Pratama</h4>
                            <p class="text-xs text-slate-400 font-medium tracking-tight">Kelas XII RPL 1</p>
                        </div>
                        <div class="text-right">
                            <p class="font-black text-indigo-600">92.4</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Skor SAW</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- School Progress -->
        <div class="bg-indigo-900 rounded-[2rem] p-8 text-white shadow-2xl shadow-indigo-200/50 relative overflow-hidden flex flex-col justify-between">
            <div class="relative z-10">
                <h3 class="text-xl font-bold mb-2 tracking-tight">Progress Pengisian Raport</h3>
                <p class="text-indigo-200 text-sm leading-relaxed italic opacity-80 mb-8 font-medium">Update real-time aktivitas guru wali kelas.</p>
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between text-xs font-bold uppercase tracking-widest mb-2">
                            <span class="text-indigo-100">Kelas XII</span>
                            <span>100% Selesai</span>
                        </div>
                        <div class="h-2.5 bg-indigo-950/50 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-400 rounded-full w-[100%] shadow-[0_0_10px_rgba(52,211,153,0.5)]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
