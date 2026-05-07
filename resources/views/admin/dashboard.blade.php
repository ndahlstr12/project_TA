@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<!-- Quick Action Bar -->
<div class="mb-8 flex flex-wrap gap-4">
    <a href="{{ route('admin.reports.index') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition flex items-center">
        <i class="fas fa-chart-pie mr-2"></i> Lihat Laporan Sekolah
    </a>
</div>

<!-- Welcome Section -->
<div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Selamat Datang, Admin!</h2>
        <p class="text-slate-500 mt-1 font-medium italic">"Pendidikan adalah paspor untuk masa depan."</p>
    </div>
    <div class="flex items-center space-x-3">
        <div class="bg-white px-4 py-2.5 rounded-xl border border-slate-200 shadow-sm flex items-center space-x-3">
            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
            <span class="text-sm font-bold text-slate-600 uppercase tracking-wider">T.A 2025/2026 Ganjil</span>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
    <!-- Total Siswa Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 group hover:-translate-y-1 transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="bg-blue-100 text-blue-600 w-12 h-12 rounded-2xl flex items-center justify-center text-xl group-hover:scale-110 transition shadow-sm">
                <i class="fas fa-user-graduate"></i>
            </div>
            <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2.5 py-1 rounded-full">+12% Bulan Ini</span>
        </div>
        <h3 class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Total Siswa</h3>
        <p class="text-3xl font-extrabold text-slate-800 mt-1 tracking-tight">1,240</p>
    </div>

    <!-- Total Guru Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 group hover:-translate-y-1 transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="bg-indigo-100 text-indigo-600 w-12 h-12 rounded-2xl flex items-center justify-center text-xl group-hover:scale-110 transition shadow-sm">
                <i class="fas fa-user-tie"></i>
            </div>
            <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-2.5 py-1 rounded-full">Stabil</span>
        </div>
        <h3 class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Total Guru</h3>
        <p class="text-3xl font-extrabold text-slate-800 mt-1 tracking-tight">86</p>
    </div>

    <!-- Total Kelas Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 group hover:-translate-y-1 transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="bg-emerald-100 text-emerald-600 w-12 h-12 rounded-2xl flex items-center justify-center text-xl group-hover:scale-110 transition shadow-sm">
                <i class="fas fa-door-open"></i>
            </div>
        </div>
        <h3 class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Total Kelas</h3>
        <p class="text-3xl font-extrabold text-slate-800 mt-1 tracking-tight">36</p>
    </div>

    <!-- Total Mapel Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 group hover:-translate-y-1 transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="bg-orange-100 text-orange-600 w-12 h-12 rounded-2xl flex items-center justify-center text-xl group-hover:scale-110 transition shadow-sm">
                <i class="fas fa-book"></i>
            </div>
        </div>
        <h3 class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Mata Pelajaran</h3>
        <p class="text-3xl font-extrabold text-slate-800 mt-1 tracking-tight">52</p>
    </div>
</div>

<!-- Middle Section: Recent Activity / Announcements -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Announcements (Card Based List) -->
    <div class="lg:col-span-2">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-bullhorn text-indigo-500 mr-3"></i>
                Pengumuman Sekolah
            </h3>
            <button class="text-indigo-600 text-sm font-bold hover:underline">Lihat Semua</button>
        </div>
        
        <div class="space-y-4">
            <!-- Card Item -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition group">
                <div class="flex items-start gap-4">
                    <div class="bg-indigo-50 text-indigo-500 w-14 h-14 rounded-xl flex-shrink-0 flex items-center justify-center text-xl">
                        <i class="far fa-calendar-check"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-bold text-slate-800 group-hover:text-indigo-600 transition">Pengumpulan Nilai Akhir Semester</h4>
                            <span class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-1 rounded tracking-tighter uppercase">Urgent</span>
                        </div>
                        <p class="text-sm text-slate-500 leading-relaxed mb-3">Diharapkan seluruh guru mata pelajaran untuk segera menginput nilai di sistem e-raport sebelum tanggal 15 Mei 2026.</p>
                        <div class="flex items-center text-[11px] font-bold text-slate-400 uppercase tracking-widest space-x-4">
                            <span><i class="far fa-user mr-1.5"></i> Kurikulum</span>
                            <span><i class="far fa-clock mr-1.5"></i> 2 Jam yang lalu</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Item -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition group">
                <div class="flex items-start gap-4">
                    <div class="bg-emerald-50 text-emerald-500 w-14 h-14 rounded-xl flex-shrink-0 flex items-center justify-center text-xl">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-bold text-slate-800 group-hover:text-emerald-600 transition">Sinkronisasi Data Dapodik</h4>
                            <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-1 rounded tracking-tighter uppercase">Berhasil</span>
                        </div>
                        <p class="text-sm text-slate-500 leading-relaxed mb-3">Seluruh data siswa kelas X, XI, dan XII telah berhasil disinkronkan dengan database pusat.</p>
                        <div class="flex items-center text-[11px] font-bold text-slate-400 uppercase tracking-widest space-x-4">
                            <span><i class="far fa-user mr-1.5"></i> Admin IT</span>
                            <span><i class="far fa-clock mr-1.5"></i> 1 Hari yang lalu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Side Card: Quick Actions / Calendar -->
    <div class="lg:col-span-1">
        <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
            <i class="fas fa-bolt text-amber-500 mr-3"></i>
            Aksi Cepat
        </h3>
        <div class="bg-indigo-900 rounded-3xl p-6 text-white shadow-xl shadow-indigo-200 relative overflow-hidden">
            <div class="relative z-10">
                <h4 class="font-bold text-lg mb-2">Pusat Bantuan</h4>
                <p class="text-indigo-200 text-xs leading-relaxed mb-6 italic font-medium tracking-tight">Butuh bantuan menggunakan sistem e-raport? Hubungi Tim IT sekolah.</p>
                <a href="https://wa.me/6281234567890" target="_blank" class="block w-full text-center bg-white text-indigo-900 font-bold py-3 rounded-xl hover:bg-indigo-50 transition shadow-lg">
                    <i class="fab fa-whatsapp mr-2"></i> WhatsApp Support
                </a>
            </div>
            <!-- Decorative Circle -->
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-500/20 rounded-full"></div>
        </div>

        <div class="mt-8 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <h4 class="font-bold text-slate-800 mb-4 tracking-tight">Log Aktivitas Anda</h4>
            <div class="space-y-4">
                <div class="flex items-center space-x-3 text-sm">
                    <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                    <p class="text-slate-600"><span class="font-bold">Anda</span> login ke sistem</p>
                    <span class="text-[10px] text-slate-400 font-bold ml-auto">08:30</span>
                </div>
                <div class="flex items-center space-x-3 text-sm">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    <p class="text-slate-600"><span class="font-bold text-slate-600">Anda</span> merubah status T.A</p>
                    <span class="text-[10px] text-slate-400 font-bold ml-auto">09:15</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
