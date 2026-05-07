<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - E-Raport SMKN 1 Sungailiat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-slate-700" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden">
        </div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="w-72 bg-indigo-950 text-white fixed inset-y-0 left-0 z-40 lg:translate-x-0 transform transition-transform duration-300 ease-in-out lg:flex flex-col h-full shadow-2xl shadow-indigo-900/20">
            <div class="p-8 border-b border-indigo-900/50 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-indigo-500 p-2 rounded-xl shadow-lg shadow-indigo-500/30">
                        <i class="fas fa-graduation-cap text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight">E-RAPORT</h1>
                        <p class="text-[10px] text-indigo-300 font-medium tracking-[0.2em] uppercase">SMKN 1 Sungailiat</p>
                    </div>
                </div>
                <!-- Close Button (Mobile Only) -->
                <button @click="sidebarOpen = false" class="lg:hidden text-indigo-300 hover:text-white transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <p class="text-[11px] text-indigo-400 font-bold uppercase tracking-widest px-4 mb-4">Main Menu</p>
                
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3.5 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-600/30' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }} rounded-xl transition-all">
                    <i class="fas fa-th-large"></i>
                    <span class="font-semibold {{ request()->routeIs('admin.dashboard') ? 'text-white' : '' }}">Dashboard Admin</span>
                </a>
                
                <p class="text-[11px] text-indigo-400 font-bold uppercase tracking-widest px-4 pt-6 mb-4">Manajemen Sistem</p>
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3.5 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-600/30' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }} rounded-xl transition-all group">
                    <i class="fas fa-users-cog group-hover:scale-110 transition"></i>
                    <span class="font-medium {{ request()->routeIs('admin.users.*') ? 'text-white' : '' }}">Manajemen User</span>
                </a>

                <p class="text-[11px] text-indigo-400 font-bold uppercase tracking-widest px-4 pt-6 mb-4">Akademik</p>
                <a href="{{ route('admin.jadwal.index') }}" class="flex items-center space-x-3 px-4 py-3.5 {{ request()->routeIs('admin.jadwal.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-600/30' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }} rounded-xl transition-all group">
                    <i class="fas fa-calendar-alt group-hover:scale-110 transition"></i>
                    <span class="font-medium {{ request()->routeIs('admin.jadwal.*') ? 'text-white' : '' }}">Jadwal Sekolah</span>
                </a>


                <p class="text-[11px] text-indigo-400 font-bold uppercase tracking-widest px-4 pt-6 mb-4">Sistem Keputusan (SAW)</p>

                <a href="{{ route('admin.kriteria.index') }}" class="flex items-center space-x-3 px-4 py-3.5 {{ request()->routeIs('admin.kriteria.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-600/30' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }} rounded-xl transition-all group">
                    <i class="fas fa-list-check group-hover:scale-110 transition"></i>
                    <span class="font-medium {{ request()->routeIs('admin.kriteria.*') ? 'text-white' : '' }}">Kriteria & Bobot</span>
                </a>
                @endif

                @if(Auth::user()->role === 'guru' || Auth::user()->role === 'walikelas')
                <a href="{{ Auth::user()->role === 'guru' ? route('guru.dashboard') : route('walikelas.dashboard') }}" class="flex items-center space-x-3 px-4 py-3.5 {{ (request()->routeIs('guru.dashboard') || request()->routeIs('walikelas.dashboard')) ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-600/30' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }} rounded-xl transition-all">
                    <i class="fas fa-th-large"></i>
                    <span class="font-semibold {{ (request()->routeIs('guru.dashboard') || request()->routeIs('walikelas.dashboard')) ? 'text-white' : '' }}">Dashboard Guru</span>
                </a>
                <a href="{{ Auth::user()->role === 'guru' ? route('guru.cbt.index') : route('walikelas.cbt.index') }}" class="flex items-center space-x-3 px-4 py-3.5 {{ request()->routeIs('*.cbt.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-600/30' : 'text-indigo-200 hover:bg-indigo-900/50 hover:text-white' }} rounded-xl transition-all group">
                    <i class="fas fa-question-circle group-hover:scale-110 transition"></i>
                    <span class="font-medium {{ request()->routeIs('*.cbt.*') ? 'text-white' : '' }}">Kelola Soal CBT</span>
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
            <header class="bg-white/80 backdrop-blur-md sticky top-0 z-10 border-b border-slate-200 px-4 sm:px-8 py-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-4">
                    <!-- Mobile Menu Toggle -->
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <i class="fas fa-bars text-lg"></i>
                    </button>

                    <div class="flex items-center space-x-2 text-slate-400">
                        <i class="fas fa-home text-sm hidden xs:block"></i>
                        <i class="fas fa-chevron-right text-[10px] hidden xs:block"></i>
                        <span class="text-sm font-semibold text-slate-600 truncate max-w-[120px] sm:max-w-none">@yield('page_title', 'Dashboard')</span>
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-3 pl-6 border-l border-slate-200 focus:outline-none group">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-slate-700 leading-tight group-hover:text-indigo-600 transition-colors">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-tighter">{{ ucfirst(Auth::user()->role) }}</p>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" alt="Profile" class="w-10 h-10 rounded-xl shadow-md group-hover:ring-2 group-hover:ring-indigo-500 transition-all">
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-56 origin-top-right bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50"
                             style="display: none;">
                            <div class="px-4 py-3 border-b border-slate-50 mb-1">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Menu Akun</p>
                            </div>
                            <a href="{{ route('profile.index') }}" class="flex items-center space-x-3 px-4 py-3 text-sm font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                                    <i class="fas fa-user-edit text-xs"></i>
                                </div>
                                <span>Edit Profil</span>
                            </a>
                            <div class="border-t border-slate-50 mt-1 pt-1">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-sm font-bold text-rose-600 hover:bg-rose-50 transition-all">
                                        <div class="w-8 h-8 bg-rose-100 rounded-lg flex items-center justify-center text-rose-600">
                                            <i class="fas fa-sign-out-alt text-xs"></i>
                                        </div>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
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
