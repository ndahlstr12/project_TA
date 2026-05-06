<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - E-Raport SMKN 1 Sungailiat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-slate-700">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-72 bg-indigo-950 text-white hidden lg:flex flex-col fixed h-full z-20 shadow-2xl transition-all duration-300">
            <div class="p-8 border-b border-indigo-900/50">
                <div class="flex items-center space-x-3">
                    <div class="bg-indigo-500 p-2 rounded-xl shadow-lg shadow-indigo-500/30">
                        <i class="fas fa-graduation-cap text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight">E-RAPORT</h1>
                        <p class="text-[10px] text-indigo-300 font-medium tracking-[0.2em] uppercase">SMKN 1 Sungailiat</p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <p class="text-[11px] text-indigo-400 font-bold uppercase tracking-widest px-4 mb-4">Main Menu</p>
                
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3.5 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-600/30' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }} rounded-xl transition-all">
                    <i class="fas fa-th-large"></i>
                    <span class="font-semibold {{ request()->routeIs('admin.dashboard') ? 'text-white' : '' }}">Dashboard Admin</span>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3.5 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-600/30' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }} rounded-xl transition-all group">
                    <i class="fas fa-users-cog group-hover:scale-110 transition"></i>
                    <span class="font-medium {{ request()->routeIs('admin.users.*') ? 'text-white' : '' }}">Manajemen User</span>
                </a>

                <a href="{{ route('admin.gurus.index') }}" class="flex items-center space-x-3 px-4 py-3.5 {{ request()->routeIs('admin.gurus.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-600/30' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }} rounded-xl transition-all group">
                    <i class="fas fa-users group-hover:scale-110 transition"></i>
                    <span class="font-medium {{ request()->routeIs('admin.gurus.*') ? 'text-white' : '' }}">Data Guru</span>
                </a>

                <a href="{{ route('admin.siswas.index') }}" class="flex items-center space-x-3 px-4 py-3.5 {{ request()->routeIs('admin.siswas.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-600/30' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }} rounded-xl transition-all group">
                    <i class="fas fa-user-graduate group-hover:scale-110 transition"></i>
                    <span class="font-medium {{ request()->routeIs('admin.siswas.*') ? 'text-white' : '' }}">Data Siswa</span>
                </a>

                <p class="text-[11px] text-indigo-400 font-bold uppercase tracking-widest px-4 pt-6 mb-4">Sistem Keputusan (SAW)</p>

                <a href="{{ route('admin.kriteria.index') }}" class="flex items-center space-x-3 px-4 py-3.5 {{ request()->routeIs('admin.kriteria.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-600/30' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }} rounded-xl transition-all group">
                    <i class="fas fa-list-check group-hover:scale-110 transition"></i>
                    <span class="font-medium {{ request()->routeIs('admin.kriteria.*') ? 'text-white' : '' }}">Kriteria & Bobot</span>
                </a>
                @endif

                @if(Auth::user()->role === 'guru' || Auth::user()->role === 'walikelas')
                <a href="{{ Auth::user()->role === 'guru' ? route('guru.dashboard') : route('walikelas.dashboard') }}" class="flex items-center space-x-3 px-4 py-3.5 bg-indigo-600/20 text-indigo-400 border border-indigo-600/30 rounded-xl transition-all">
                    <i class="fas fa-th-large"></i>
                    <span class="font-semibold text-white">Dashboard Guru</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3.5 text-indigo-200 hover:bg-indigo-900/50 hover:text-white rounded-xl transition-all group">
                    <i class="fas fa-book group-hover:scale-110 transition"></i>
                    <span class="font-medium">Input Nilai Raport</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3.5 text-indigo-200 hover:bg-indigo-900/50 hover:text-white rounded-xl transition-all group">
                    <i class="fas fa-calendar-alt group-hover:scale-110 transition"></i>
                    <span class="font-medium">Jadwal Mengajar</span>
                </a>
                @endif

                @if(Auth::user()->role === 'siswa' || Auth::user()->role === 'orangtua')
                <a href="{{ Auth::user()->role === 'siswa' ? route('siswa.dashboard') : route('parent.dashboard') }}" class="flex items-center space-x-3 px-4 py-3.5 bg-indigo-600/20 text-indigo-400 border border-indigo-600/30 rounded-xl transition-all">
                    <i class="fas fa-th-large"></i>
                    <span class="font-semibold text-white">Dashboard {{ Auth::user()->role === 'siswa' ? 'Siswa' : 'Orang Tua' }}</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3.5 text-indigo-200 hover:bg-indigo-900/50 hover:text-white rounded-xl transition-all group">
                    <i class="fas fa-file-invoice group-hover:scale-110 transition"></i>
                    <span class="font-medium">Lihat Raport</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3.5 text-indigo-200 hover:bg-indigo-900/50 hover:text-white rounded-xl transition-all group">
                    <i class="fas fa-info-circle group-hover:scale-110 transition"></i>
                    <span class="font-medium">Informasi Akademik</span>
                </a>
                @endif

                <p class="text-[11px] text-indigo-400 font-bold uppercase tracking-widest px-4 pt-6 mb-4">Pusat Data & Pesan</p>

                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.reports.index') }}" class="flex items-center space-x-3 px-4 py-3.5 {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-600/30' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }} rounded-xl transition-all group">
                    <i class="fas fa-chart-pie group-hover:scale-110 transition"></i>
                    <span class="font-medium {{ request()->routeIs('admin.reports.*') ? 'text-white' : '' }}">Laporan Sekolah</span>
                </a>
                @endif

                <a href="{{ Auth::user()->role === 'admin' ? route('admin.notifications.index') : '#' }}" class="flex items-center space-x-3 px-4 py-3.5 {{ request()->routeIs('admin.notifications.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-600/30' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }} rounded-xl transition-all group">
                    <i class="fas fa-bell group-hover:scale-110 transition"></i>
                    <span class="font-medium {{ request()->routeIs('admin.notifications.*') ? 'text-white' : '' }}">Notifikasi</span>
                </a>
            </nav>

            <div class="p-6 border-t border-indigo-900/50">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white py-3 rounded-xl transition-all duration-300 group">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="font-bold">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Content Area -->
        <main class="flex-1 lg:ml-72 transition-all duration-300">
            <!-- Header / Topbar -->
            <header class="bg-white/80 backdrop-blur-md sticky top-0 z-10 border-b border-slate-200 px-8 py-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-2 text-slate-400">
                    <i class="fas fa-home text-sm"></i>
                    <i class="fas fa-chevron-right text-[10px]"></i>
                    <span class="text-sm font-semibold text-slate-600">@yield('page_title', 'Dashboard')</span>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-3 pl-6 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-slate-700 leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] font-medium text-slate-400 uppercase tracking-tighter">{{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" alt="Profile" class="w-10 h-10 rounded-xl shadow-md">
                    </div>
                </div>
            </header>

            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
