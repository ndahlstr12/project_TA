<!DOCTYPE html>
<html lang="id" x-data="{ 
    darkMode: localStorage.getItem('darkMode') === 'true',
    role: 'siswa',
    loading: false,
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
    }
}" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | E-Raport Pro</title>
    
    <!-- Aesthetic Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        accent: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                        },
                        surface: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        body {
            @apply bg-slate-50 dark:bg-surface-950 text-slate-900 dark:text-slate-100 antialiased;
        }

        .card-soft {
            @apply bg-white/80 dark:bg-surface-900/80 backdrop-blur-2xl border border-white dark:border-white/5 rounded-[2.5rem] shadow-2xl shadow-slate-200/60 dark:shadow-none;
        }

        .input-soft {
            @apply w-full px-6 py-4 bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 rounded-2xl text-sm font-semibold outline-none transition-all duration-300;
            @apply focus:ring-4 focus:ring-accent-500/10 focus:border-accent-500 focus:bg-white;
        }
    </style>
</head>
<body class="selection:bg-accent-100 selection:text-accent-600 flex items-center justify-center min-h-screen relative p-6">

    <!-- Ambient Background Decorations -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[40%] h-[40%] bg-accent-100/40 rounded-full blur-[120px] dark:bg-accent-900/20"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[40%] h-[40%] bg-indigo-100/30 rounded-full blur-[120px] dark:bg-indigo-900/10"></div>
    </div>

    <!-- Theme Toggle -->
    <div class="absolute top-8 right-8 z-50">
        <button @click="toggleDarkMode()" class="p-4 bg-white/50 dark:bg-white/5 backdrop-blur-md rounded-2xl shadow-sm text-slate-400 hover:text-accent-500 transition-all border border-white/50">
            <i x-show="!darkMode" data-lucide="moon" class="w-5 h-5"></i>
            <i x-show="darkMode" data-lucide="sun" class="w-5 h-5"></i>
        </button>
    </div>

    <div class="w-full max-w-2xl relative">
        <!-- Branding -->
        <div class="flex flex-col items-center mb-10">
            <div class="w-14 h-14 bg-accent-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-accent-500/30 mb-4">
                <i data-lucide="graduation-cap" class="w-8 h-8"></i>
            </div>
            <h1 class="text-xl font-black tracking-tight text-slate-900 dark:text-white uppercase">E-Raport Pro</h1>
        </div>

        <div class="card-soft p-10 lg:p-12">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-2">Daftar Akun</h2>
                <p class="text-sm text-slate-400 font-medium">Buat akun untuk mengakses portal akademik digital.</p>
            </div>

            @if($errors->any())
            <div class="mb-8 p-5 bg-rose-50 dark:bg-rose-900/20 rounded-2xl border border-rose-100 dark:border-rose-800/50 space-y-2">
                @foreach($errors->all() as $error)
                <div class="flex gap-3 items-center">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-500"></i>
                    <p class="text-xs text-rose-700 dark:text-rose-400 font-bold leading-tight">{{ $error }}</p>
                </div>
                @endforeach
            </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-8" @submit="loading = true">
                @csrf
                
                <!-- Role Selector -->
                <div class="flex p-2 bg-slate-50 dark:bg-white/5 rounded-[1.25rem] mb-10">
                    <button type="button" @click="role = 'siswa'" 
                        :class="role === 'siswa' ? 'bg-white dark:bg-surface-800 text-accent-600 shadow-xl shadow-slate-200/50 dark:shadow-none' : 'text-slate-400'"
                        class="flex-1 py-3 text-xs font-black uppercase tracking-widest rounded-2xl transition-all duration-500">
                        Siswa
                    </button>
                    <button type="button" @click="role = 'guru'" 
                        :class="role === 'guru' ? 'bg-white dark:bg-surface-800 text-accent-600 shadow-xl shadow-slate-200/50 dark:shadow-none' : 'text-slate-400'"
                        class="flex-1 py-3 text-xs font-black uppercase tracking-widest rounded-2xl transition-all duration-500">
                        Guru / Staf
                    </button>
                </div>
                <input type="hidden" name="role" :value="role">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- ID Number -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-4" 
                               x-text="role === 'siswa' ? 'NISN Siswa' : 'NIP Pengajar'"></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-accent-500 transition-colors">
                                <i data-lucide="id-card" class="w-5 h-5"></i>
                            </div>
                            <input type="text" name="id_number" required autofocus
                                class="input-soft pl-14"
                                :placeholder="role === 'siswa' ? '10 Digit NISN' : 'NIP Terdaftar'">
                        </div>
                    </div>

                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-4">Nama Lengkap</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-accent-500 transition-colors">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <input type="text" name="name" required
                                class="input-soft pl-14"
                                placeholder="Nama Lengkap">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-4">Kata Sandi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-accent-500 transition-colors">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </div>
                            <input type="password" name="password" required
                                class="input-soft pl-14"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-4">Konfirmasi Sandi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-accent-500 transition-colors">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                            </div>
                            <input type="password" name="password_confirmation" required
                                class="input-soft pl-14"
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" :disabled="loading"
                        class="w-full bg-accent-600 hover:bg-accent-700 disabled:opacity-70 text-white font-bold py-5 rounded-2xl shadow-xl shadow-accent-500/30 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                        <template x-if="!loading">
                            <span class="flex items-center gap-3 text-sm uppercase tracking-widest">
                                Daftar Sekarang
                                <i data-lucide="arrow-right" class="w-5 h-5"></i>
                            </span>
                        </template>
                        <template x-if="loading">
                            <i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i>
                        </template>
                    </button>
                </div>
            </form>

            <div class="mt-12 text-center border-t border-slate-50 dark:border-white/5 pt-8">
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest">
                    Sudah terdaftar? <a href="{{ route('login') }}" class="text-accent-600 hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>


        <p class="mt-12 text-center text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em]">
            &copy; 2026 TIM IT SMKN 1 SUNGAILIAT
        </p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
