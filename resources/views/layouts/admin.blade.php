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
            /* Mode Gelap Overrides */
            --nav-bg: #0f172a;
            --nav-surface: #1e293b;
            --nav-hover: #263348;
            --nav-active-bg: rgba(29, 78, 216, 0.15);
            --nav-text: #94a3b8;
            --nav-text-active: #f1f5f9;
            --nav-label: #475569;
            --nav-accent: #3b82f6;
            --nav-border: rgba(255, 255, 255, 0.06);
        }

        body {
            @apply bg-slate-50 dark:bg-[#0b1120] text-slate-900 dark:text-slate-100 antialiased font-sans transition-colors duration-300;
            display: flex;
            min-height: 100vh;
            margin: 0;
        }

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

    <!-- Mobile toggle button -->
    <button class="lg:hidden fixed top-4 left-4 z-[100] bg-slate-800 border border-white/5 p-2 rounded-lg text-white" @click="mobileSidebarOpen = !mobileSidebarOpen">
        <i class="ti ti-menu-2"></i>
    </button>

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
                    $groups = [
                        'Menu Utama' => [
                            ['label' => 'Beranda', 'icon' => 'ti-home', 'route' => 'admin.dashboard'],
                            ['label' => 'Notifikasi', 'icon' => 'ti-bell', 'route' => 'admin.notifications.index'],
                        ],
                        'Data Master' => [
                            ['label' => 'Manajemen Kelas', 'icon' => 'ti-category', 'route' => 'admin.kelas.index'],
                            ['label' => 'Manajemen User', 'icon' => 'ti-shield-check', 'route' => 'admin.users.index'],
                        ],
                        'Akademik' => [
                            ['label' => 'Jadwal Pelajaran', 'icon' => 'ti-calendar-event', 'route' => 'admin.jadwal.index'],
                            ['label' => 'Kriteria SPK', 'icon' => 'ti-list-check', 'route' => 'admin.kriteria.index'],
                        ]
                    ];
                @endphp

                @foreach($groups as $title => $items)
                @if($title)
                <div class="nav-section-label @if(!$loop->first) mt-2 @endif">{{ $title }}</div>
                @endif
                @foreach($items as $item)
                <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
                   class="nav-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                    <span class="nav-icon"><i class="ti {{ $item['icon'] }}"></i></span>
                    <span class="nav-label-text">{{ $item['label'] }}</span>
                    <span class="tooltip">{{ $item['label'] }}</span>
                </a>
                @endforeach
                @endforeach
            </nav>

            <!-- Footer / User -->
            <div class="sidebar-footer">
                <div class="user-card" x-data="{ open: false }" @click="open = !open">
                    <div class="avatar">{{ substr(Auth::user()->name, 0, 2) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">Admin Sistem</div>
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
        <header class="h-[72px] px-8 flex items-center justify-between shrink-0 bg-white/80 dark:bg-[#0f172a]/50 backdrop-blur-md border-b border-slate-200 dark:border-white/5 transition-colors duration-300 relative z-40">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">@yield('page_title', 'Dashboard')</h2>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
            </div>
            
            <div class="flex items-center gap-4">
                <button @click="toggleDarkMode()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:text-blue-500 transition-colors">
                    <i x-show="!darkMode" class="ti ti-moon text-xl"></i>
                    <i x-show="darkMode" class="ti ti-sun text-xl"></i>
                </button>

                <div class="w-px h-6 bg-slate-200 dark:bg-white/10 mx-1"></div>

                <div class="relative" x-data="{ userOpen: false }">
                    <button @click="userOpen = !userOpen" class="flex items-center gap-3 p-1.5 pr-3 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 transition-all">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1d4ed8&color=fff" 
                             class="w-9 h-9 rounded-lg shadow-sm">
                        <div class="hidden md:block text-left">
                            <p class="text-xs font-bold text-slate-900 dark:text-white leading-none">{{ explode(' ', Auth::user()->name)[0] }}</p>
                            <p class="text-[10px] font-bold text-blue-500 uppercase tracking-tighter mt-1">Admin Sistem</p>
                        </div>
                        <i class="ti ti-chevron-down text-xs text-slate-400 transition-transform" :class="userOpen ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="userOpen" @click.away="userOpen = false" x-cloak x-transition class="absolute right-0 mt-3 w-56 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-2xl shadow-2xl p-2 z-50">
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
        <main class="flex-1 overflow-y-auto custom-scrollbar p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
