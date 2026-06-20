<!DOCTYPE html>
<html lang="id" x-data="{ 
    darkMode: localStorage.getItem('darkMode') === 'true',
    sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
    mobileSidebarOpen: false,
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
    },
    toggleSidebar() {
        this.sidebarCollapsed = !this.sidebarCollapsed;
        localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
    }
}" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | SMKN 1 Sungailiat</title>
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        merri: ['Merriweather', 'serif'],
                    },
                    colors: {
                        navy: {
                            800: '#1a2c5b',
                            900: '#0f1d3d',
                            950: '#081026',
                        },
                        gold: {
                            50: '#fdf8f0',
                            100: '#fbeed9',
                            200: '#f5c55a',
                            300: '#f2bd5a',
                            400: '#f0b541',
                            500: '#e8a020',
                            600: '#ca8218',
                            700: '#a86217',
                        }
                    }
                }
            }
        }
    </script>
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <style>
        [x-cloak] { display: none !important; }

        :root {
            /* Mode Terang (Default) */
            --nav-bg: #ffffff;
            --nav-surface: #f8fafc;
            --nav-hover: #f1f5f9;
            --nav-active-bg: rgba(59, 130, 246, 0.1);
            --nav-text: #64748b;
            --nav-text-active: #0f172a;
            --nav-label: #94a3b8;
            --nav-accent: #3b82f6;
            --nav-border: rgba(0, 0, 0, 0.05);
            --sidebar-width: 260px;
            --sidebar-collapsed: 72px;
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dark {
            /* Mode Gelap Premium - Lebih Dalam & Kontras Tinggi */
            --nav-bg: #0f172a;
            --nav-surface: #1e293b;
            --nav-hover: #263348;
            --nav-active-bg: rgba(59, 130, 246, 0.15);
            --nav-text: #94a3b8;
            --nav-text-active: #f1f5f9;
            --nav-label: #64748b; /* Dipertajam dari #475569 */
            --nav-accent: #3b82f6;
            --nav-border: rgba(255, 255, 255, 0.08);
        }

        body {
            @apply bg-slate-50 dark:bg-[#0b1120] text-slate-900 dark:text-slate-100 antialiased font-sans transition-colors duration-300;
            display: flex;
            min-height: 100vh;
            margin: 0;
        }

        /* Utility for Dark Text Contrast */
        .dark .text-navy-400 { color: #94a3b8; }
        .dark .text-navy-300 { color: #64748b; }
        .dark .text-navy-100 { color: #cbd5e1; }
        .dark .border-base { border-color: rgba(255, 255, 255, 0.08); }
        .dark .bg-base { background-color: rgba(255, 255, 255, 0.03); }

        /* ===== SIDEBAR WRAPPER ===== */
        .sidebar-wrap {
            position: relative;
            flex-shrink: 0;
            z-index: 50;
            transition: all var(--transition);
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--nav-bg);
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--nav-border);
            transition: width var(--transition), background var(--transition), border var(--transition);
            overflow: hidden;
            position: sticky;
            top: 0;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 16px;
            border-bottom: 1px solid var(--nav-border);
            min-height: 72px;
            flex-shrink: 0;
            transition: border var(--transition);
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            background: var(--nav-accent);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #fff;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .logo-text h1 {
            font-size: 15px;
            font-weight: 700;
            color: var(--nav-text-active);
            letter-spacing: 0.5px;
            margin: 0;
            transition: color var(--transition);
        }

        .logo-text span {
            font-size: 11px;
            color: var(--nav-label);
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 2px;
            transition: color var(--transition);
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #22c55e;
            flex-shrink: 0;
        }

        .nav-scroll {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 12px 0;
            scrollbar-width: none;
        }

        .nav-scroll::-webkit-scrollbar {
            display: none;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--nav-label);
            padding: 10px 20px 4px;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity var(--transition), color var(--transition);
        }

        .sidebar.collapsed .nav-section-label {
            opacity: 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            margin: 2px 8px;
            border-radius: 10px;
            color: var(--nav-text);
            text-decoration: none;
            cursor: pointer;
            transition: background var(--transition), color var(--transition), transform 0.1s;
            white-space: nowrap;
            position: relative;
            border: none;
            background: transparent;
            width: calc(100% - 16px);
            text-align: left;
        }

        .nav-item:hover {
            background: var(--nav-hover);
            color: var(--nav-text-active);
            transform: translateX(2px);
        }

        .nav-item.active {
            background: var(--nav-active-bg);
            color: var(--nav-accent);
            font-weight: 500;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: var(--nav-accent);
            border-radius: 0 3px 3px 0;
        }

        .sidebar.collapsed .nav-item::before {
            display: none;
        }
/* ===== NAV ICON ===== */
.nav-icon {
    font-size: 20px;
    flex-shrink: 0;
    width: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color var(--transition);
    position: relative;
}

.notif-badge {
    @apply absolute -top-1.5 -right-2 bg-rose-500 text-white text-[9px] font-black w-4 h-4 flex items-center justify-center rounded-full border-2 transition-all;
    border-color: var(--nav-bg);
}

        .nav-label-text {
            font-size: 13.5px;
            overflow: hidden;
            transition: opacity var(--transition), max-width var(--transition);
            max-width: 200px;
        }

        .sidebar.collapsed .nav-label-text {
            opacity: 0;
            max-width: 0;
        }

        .tooltip {
            display: none;
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: #1e293b;
            color: #f1f5f9;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 6px;
            border: 0.5px solid var(--nav-border);
            pointer-events: none;
            z-index: 100;
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-item:hover .tooltip {
            display: block;
        }

        .toggle-btn {
            position: absolute;
            bottom: 84px;
            right: -12px;
            width: 24px;
            height: 24px;
            background: var(--nav-surface);
            border: 1px solid var(--nav-border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background var(--transition);
            z-index: 10;
            color: var(--nav-text);
            font-size: 14px;
        }

        .toggle-btn:hover {
            background: var(--nav-hover);
            color: var(--nav-text-active);
        }

        .sidebar-footer {
            border-top: 1px solid var(--nav-border);
            padding: 12px;
            flex-shrink: 0;
            transition: border var(--transition);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 10px;
            background: var(--nav-surface);
            cursor: pointer;
            transition: background var(--transition);
            overflow: hidden;
            position: relative;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            position: relative;
        }

        .avatar::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #22c55e;
            border: 2px solid var(--nav-bg);
        }

        .user-info {
            overflow: hidden;
            transition: opacity var(--transition), max-width var(--transition);
            max-width: 200px;
        }

        .sidebar.collapsed .user-info {
            opacity: 0;
            max-width: 0;
        }

        .user-name {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--nav-text-active);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
            transition: color var(--transition);
        }

        .user-role {
            font-size: 11px;
            color: var(--nav-accent);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .custom-scrollbar::-webkit-scrollbar { width: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { @apply bg-slate-200 dark:bg-white/10 rounded-full; }

        @media (max-width: 768px) {
            .sidebar-wrap {
                position: fixed;
                left: -260px;
                transition: left var(--transition);
            }
            .sidebar-wrap.mobile-open {
                left: 0;
            }
        }

        /* Card Styles Adaptation */
        .card-pro {
            @apply bg-white dark:bg-[#1e293b] border border-slate-200 dark:border-white/5 rounded-2xl shadow-sm dark:shadow-none transition-all duration-300;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Overlay untuk mobile -->
    <div class="fixed inset-0 bg-black/50 z-[90] hidden" :class="{ 'block': mobileSidebarOpen }" @click="mobileSidebarOpen = false"></div>

    <div class="sidebar-wrap flex-shrink-0 relative" :class="{ 'mobile-open': mobileSidebarOpen }">
        <aside class="sidebar" :class="{ 'collapsed': sidebarCollapsed }">

            <!-- Header -->
            <div class="sidebar-header">
                <div class="logo-icon">
                    <i class="ti ti-layers-intersect"></i>
                </div>
                <div class="logo-text">
                    <h1>E-RAPORT.</h1>
                    <span><span class="status-dot"></span> SMKN 1 Sungailiat</span>
                </div>
            </div>

            <!-- Nav -->
            <nav class="nav-scroll">
                @php
                    $role = Auth::user()->role;
                    $homeRoute = $role === 'orangtua' ? 'parent.dashboard' : $role . '.dashboard';
                    
                    $menuItems = [
                        ['label' => 'Beranda', 'icon' => 'ti-home', 'route' => $homeRoute],
                    ];

                    if ($role === 'admin') {
                        $menuItems = array_merge($menuItems, [
                            ['label' => 'Konfigurasi Akademik', 'icon' => 'ti-settings-automation', 'route' => 'admin.settings.index', 'group' => 'Menu Utama'],
                            ['label' => 'Notifikasi', 'icon' => 'ti-bell', 'route' => 'admin.notifications.index', 'group' => 'Menu Utama'],
                            ['label' => 'Manajemen Kelas', 'icon' => 'ti-category', 'route' => 'admin.kelas.index', 'group' => 'Data Master'],
                            ['label' => 'Manajemen User', 'icon' => 'ti-shield-check', 'route' => 'admin.users.index', 'group' => 'Data Master'],
                            ['label' => 'Jadwal Pelajaran', 'icon' => 'ti-calendar-event', 'route' => 'admin.jadwal.index', 'group' => 'Akademik'],
                            ['label' => 'Kriteria SPK', 'icon' => 'ti-list-check', 'route' => 'admin.kriteria.index', 'group' => 'Akademik'],
                        ]);
                    } elseif ($role === 'guru' || $role === 'walikelas') {
                        $menuItems = array_merge($menuItems, [
                            ['label' => 'Manajemen Nilai', 'icon' => 'ti-book-2', 'route' => 'shared.nilai.index', 'group' => 'Akademik'],
                        ]);

                        $absensiMenu = [
                            'label' => 'Absensi Siswa', 
                            'icon' => 'ti-checkup-list', 
                            'route' => 'shared.kehadiran.index',
                            'group' => 'Akademik'
                        ];

                        if ($role === 'walikelas') {
                            $absensiMenu['submenu'] = [
                                ['label' => 'Input Absensi', 'icon' => 'ti-circle-check', 'route' => 'shared.kehadiran.index'],
                                ['label' => 'Rekap Kehadiran', 'icon' => 'ti-calendar-stats', 'route' => 'walikelas.raport.attendance.bulk'],
                            ];
                        }

                        $menuItems[] = $absensiMenu;
                        $menuItems[] = ['label' => 'Bank Soal CBT', 'icon' => 'ti-device-laptop', 'route' => 'shared.cbt.index', 'group' => 'Akademik'];
                        
                        if ($role === 'walikelas') {
                            $menuItems[] = ['label' => 'Jurnal Perilaku', 'icon' => 'ti-notebook', 'route' => 'walikelas.jurnal.index', 'group' => 'Akademik'];
                            $menuItems[] = ['label' => 'Manajemen Raport', 'icon' => 'ti-file-certificate', 'route' => 'walikelas.raport.index', 'group' => 'Akademik'];
                            $menuItems[] = ['label' => 'Ranking Siswa', 'icon' => 'ti-award', 'route' => 'walikelas.ranking.index', 'group' => 'Akademik'];
                        }
                    } elseif ($role === 'siswa') {
                        $menuItems = array_merge($menuItems, [
                            ['label' => 'Ujian CBT', 'icon' => 'ti-device-laptop', 'route' => 'siswa.cbt.index', 'group' => 'Akademik'],
                            ['label' => 'Jurnal Harian', 'icon' => 'ti-notebook', 'route' => 'siswa.jurnal.index', 'group' => 'Akademik'],
                            ['label' => 'E-Raport', 'icon' => 'ti-file-certificate', 'route' => 'siswa.raport.index', 'group' => 'Akademik'],
                        ]);
                    } elseif ($role === 'orangtua') {
                        $menuItems = array_merge($menuItems, [
                            ['label' => 'Nilai Anak', 'icon' => 'ti-book-2', 'route' => 'parent.nilai.index', 'group' => 'Info Akademik'],
                            ['label' => 'Jurnal Perilaku', 'icon' => 'ti-notebook', 'route' => 'parent.jurnal.index', 'group' => 'Info Akademik'],
                            ['label' => 'E-Raport', 'icon' => 'ti-file-certificate', 'route' => 'parent.raport.index', 'group' => 'Info Akademik'],
                        ]);
                    }
                    
                    // Grouping logic
                    $groupedMenu = ['Menu Utama' => []];
                    foreach($menuItems as $item) {
                        $group = $item['group'] ?? 'Menu Utama';
                        $groupedMenu[$group][] = $item;
                    }
                @endphp

                @foreach($groupedMenu as $title => $items)
                @if(count($items) > 0)
                <div class="nav-section-label @if(!$loop->first) mt-2 @endif">{{ $title }}</div>
                @foreach($items as $item)
                    @if(isset($item['submenu']))
                        <div x-data="{ open: {{ collect($item['submenu'])->pluck('route')->contains(request()->route()->getName()) ? 'true' : 'false' }} }" class="space-y-1">
                            <button @click="open = !open" 
                               class="nav-item w-full flex items-center justify-between {{ collect($item['submenu'])->pluck('route')->contains(request()->route()->getName()) ? 'active' : '' }}">
                                <div class="flex items-center gap-3">
                                    <span class="nav-icon"><i class="ti {{ $item['icon'] }}"></i></span>
                                    <span class="nav-label-text">{{ $item['label'] }}</span>
                                </div>
                                <i class="ti ti-chevron-down text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-4 pl-4 border-l border-slate-200 dark:border-white/5 space-y-1">
                                @foreach($item['submenu'] as $sub)
                                <a href="{{ Route::has($sub['route']) ? route($sub['route']) : '#' }}" 
                                   class="flex items-center gap-3 px-4 py-2 rounded-lg text-xs font-bold transition-all {{ request()->routeIs($sub['route']) ? 'text-blue-500 bg-blue-500/5' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/5' }}">
                                    <i class="ti {{ $sub['icon'] }} text-sm"></i>
                                    {{ $sub['label'] }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
                           class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                            <span class="nav-icon"><i class="ti {{ $item['icon'] }}"></i></span>
                            <span class="nav-label-text">{{ $item['label'] }}</span>
                            <span class="tooltip">{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
                @endif
                @endforeach
            </nav>

            <!-- Footer / User -->
            <div class="sidebar-footer">
                <div class="user-card" x-data="{ open: false }" @click="open = !open">
                    @if(Auth::user()->foto)
                        <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                    @else
                        <div class="avatar">{{ substr(Auth::user()->name, 0, 2) }}</div>
                    @endif
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">{{ str_replace('_', ' ', strtoupper(Auth::user()->role)) }}</div>
                    </div>

                    <!-- Dropdown for Logout/Profile -->
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute bottom-full left-2 mb-2 w-48 bg-slate-800 border border-white/5 rounded-xl shadow-2xl p-2 z-[100]">
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-bold text-slate-300 hover:bg-white/5 rounded-lg transition-all">
                            <i class="ti ti-user"></i> Profil Saya
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-xs font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-all text-left">
                                <i class="ti ti-logout"></i> Keluar Sistem
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Toggle collapse button (desktop) -->
            <button class="toggle-btn" @click="toggleSidebar()" title="Kecilkan Sidebar">
                <i class="ti ti-chevron-left transition-transform duration-300" :style="sidebarCollapsed ? 'transform: rotate(180deg)' : ''"></i>
            </button>
        </aside>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper flex-1 flex flex-col min-w-0 bg-slate-50 dark:bg-[#0b1120] transition-colors duration-300">
        
        <!-- Nav Top area -->
        <header class="h-[72px] px-4 md:px-8 flex items-center justify-between shrink-0 bg-white/80 dark:bg-[#0f172a]/50 backdrop-blur-md border-b border-slate-200 dark:border-white/5 transition-colors duration-300 relative z-40">
            <div class="flex items-center gap-3">
                <button class="lg:hidden w-10 h-10 flex items-center justify-center bg-slate-800 border border-white/5 rounded-xl text-white shadow-lg active:scale-95 transition-all" @click="mobileSidebarOpen = !mobileSidebarOpen">
                    <i class="ti ti-menu-2 text-xl"></i>
                </button>
                <div class="min-w-0">
                    <h2 class="text-sm md:text-lg font-bold text-slate-900 dark:text-white truncate">@yield('page_title', 'Dashboard')</h2>
                    <p class="text-[8px] md:text-[10px] font-bold text-slate-500 uppercase tracking-widest truncate">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2 md:gap-4">
                <!-- Notifications -->
                <div class="relative" x-data="notificationCenter()" x-init="initNotif()">
                    <button @click="notifOpen = !notifOpen" class="w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:text-blue-500 transition-colors relative">
                        <i class="ti ti-bell text-lg md:text-xl"></i>
                        <template x-if="unreadCount > 0">
                            <span class="absolute top-1.5 right-1.5 w-3.5 h-3.5 bg-rose-500 text-white text-[8px] font-black flex items-center justify-center rounded-full border-2 border-white dark:border-[#0f172a]" x-text="unreadCount">
                            </span>
                        </template>
                    </button>

                    <div x-show="notifOpen" @click.away="notifOpen = false" x-cloak x-transition class="absolute right-0 mt-3 w-72 md:w-80 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-2xl shadow-2xl overflow-hidden z-50">
                        <div class="p-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                            <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white">Notifikasi</h4>
                            <div class="flex gap-2">
                                <template x-if="unreadCount > 0">
                                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[8px] font-bold text-blue-500 uppercase hover:underline">Baca Semua</button>
                                    </form>
                                </template>
                            </div>
                        </div>
                        <div class="max-h-80 md:max-h-96 overflow-y-auto custom-scrollbar">
                            <template x-for="notif in list" :key="notif.id">
                                <div class="p-3 md:p-4 border-b border-slate-50 dark:border-white/5 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <p class="text-[9px] md:text-[10px] font-bold text-slate-900 dark:text-white" x-text="notif.title"></p>
                                    <p class="text-[9px] md:text-[10px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed" x-text="notif.message"></p>
                                    <p class="text-[7px] md:text-[8px] font-bold text-slate-400 uppercase mt-2 tracking-tighter" x-text="notif.time"></p>
                                </div>
                            </template>
                            <template x-if="list.length === 0">
                                <div class="p-8 text-center opacity-30">
                                    <i class="ti ti-bell-off text-2xl mb-2"></i>
                                    <p class="text-[9px] font-bold uppercase tracking-widest">Tidak ada notifikasi</p>
                                </div>
                            </template>
                        </div>
                        <template x-if="list.length > 0">
                            <a href="#" class="block p-3 text-center text-[9px] font-bold text-blue-500 uppercase tracking-widest bg-slate-50/50 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 transition-all">Lihat Semua</a>
                        </template>
                    </div>
                </div>

                <button @click="toggleDarkMode()" class="w-9 h-9 md:w-10 md:h-10 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:text-blue-500 transition-colors">
                    <i x-show="!darkMode" class="ti ti-moon text-lg md:text-xl"></i>
                    <i x-show="darkMode" class="ti ti-sun text-lg md:text-xl"></i>
                </button>

                <div class="hidden sm:block w-px h-6 bg-slate-200 dark:bg-white/10 mx-1"></div>

                <div class="relative" x-data="{ userOpen: false }">
                    <button @click="userOpen = !userOpen" class="flex items-center gap-2 p-1 md:p-1.5 md:pr-3 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 transition-all">
                        @if(Auth::user()->foto)
                            <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="w-8 h-8 md:w-9 md:h-9 rounded-lg object-cover shadow-sm">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1d4ed8&color=fff" 
                                 class="w-8 h-8 md:w-9 md:h-9 rounded-lg shadow-sm">
                        @endif
                        <div class="hidden lg:block text-left">
                            <p class="text-xs font-bold text-slate-900 dark:text-white leading-none">{{ explode(' ', Auth::user()->name)[0] }}</p>
                            <p class="text-[9px] font-bold text-blue-500 uppercase tracking-tighter mt-1">{{ str_replace('_', ' ', strtoupper(Auth::user()->role)) }}</p>
                        </div>
                        <i class="ti ti-chevron-down text-[10px] text-slate-400 transition-transform hidden sm:block" :class="userOpen ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="userOpen" @click.away="userOpen = false" x-cloak x-transition class="absolute right-0 mt-3 w-48 md:w-56 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-2xl shadow-2xl p-2 z-50">
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 rounded-xl transition-all">
                            <i class="ti ti-user text-base text-blue-500"></i> Profil Saya
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-xs font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-xl transition-all text-left">
                                <i class="ti ti-logout text-base"></i> Keluar Sistem
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Scrollable Content -->
        <main class="flex-1 overflow-y-auto custom-scrollbar p-4 md:p-8">
            <div class="max-w-7xl mx-auto">
                {{-- Flash Messages --}}
                @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center gap-3 text-emerald-600 animate-in fade-in slide-in-from-top-4">
                    <i class="ti ti-circle-check text-xl"></i>
                    <p class="text-xs font-bold">{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-8 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-center gap-3 text-rose-600 animate-in fade-in slide-in-from-top-4">
                    <i class="ti ti-alert-circle text-xl"></i>
                    <p class="text-xs font-bold">{{ session('error') }}</p>
                </div>
                @endif

                @if(session('info'))
                <div class="mb-8 p-4 bg-blue-500/10 border border-blue-500/20 rounded-2xl flex items-center gap-3 text-blue-600 animate-in fade-in slide-in-from-top-4">
                    <i class="ti ti-info-circle text-xl"></i>
                    <p class="text-xs font-bold">{{ session('info') }}</p>
                </div>
                @endif

                @if(Auth::check() && Auth::user()->must_change_password && Auth::user()->role !== 'admin')
                <div class="mb-8 p-5 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-800/50 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-amber-100 dark:bg-amber-800/30 text-amber-600 dark:text-amber-400 shrink-0">
                            <i class="ti ti-shield-lock text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-amber-900 dark:text-amber-100">Lindungi Akun Anda</h4>
                            <p class="text-xs text-amber-700/80 dark:text-amber-400/80 mt-1">Anda terdeteksi masih menggunakan kata sandi standar. Segera ganti kata sandi untuk mencegah akses tidak sah.</p>
                        </div>
                    </div>
                    <a href="{{ route('profile.index') }}" class="whitespace-nowrap px-6 py-2.5 text-xs font-bold bg-amber-600 hover:bg-amber-700 text-white rounded-xl transition-all shadow-md shadow-amber-600/20 flex items-center gap-2">
                        <i class="ti ti-key"></i>
                        Ganti Kata Sandi Sekarang
                    </a>
                </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 space-y-3 z-[9999] w-80 max-w-[95vw]"></div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        function showToast(title, message) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-2xl shadow-2xl flex items-start gap-3 animate-in slide-in-from-bottom-5 duration-300';
            toast.innerHTML = `
                <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-500 flex items-center justify-center shrink-0">
                    <i class="ti ti-bell"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h5 class="text-[10px] font-black uppercase tracking-widest text-slate-900 dark:text-white leading-none">${title}</h5>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-500 shrink-0">
                    <i class="ti ti-x text-xs"></i>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('animate-out', 'fade-out', 'slide-out-to-bottom-5');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        function notificationCenter() {
            return {
                notifOpen: false,
                unreadCount: {{ $unreadNotificationsCount ?? 0 }},
                list: @json($formattedNotifications ?? []),
                initNotif() {
                    // Poll unread notifications every 15 seconds
                    setInterval(() => {
                        fetch('{{ route('notifications.unread') }}')
                            .then(response => response.json())
                            .then(data => {
                                // If unread count has increased, display new toasts
                                if (data.unreadCount > this.unreadCount) {
                                    const currentIds = this.list.map(n => n.id);
                                    data.notifications.forEach(newNotif => {
                                        if (!currentIds.includes(newNotif.id) && !newNotif.is_read) {
                                            showToast(newNotif.title, newNotif.message);
                                        }
                                    });
                                }
                                this.unreadCount = data.unreadCount;
                                this.list = data.notifications;
                            })
                            .catch(err => console.error('Notification Polling Error:', err));
                    }, 15000);
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
