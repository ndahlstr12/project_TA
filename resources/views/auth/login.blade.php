<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | E-Raport SMKN 1 Sungailiat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .login-gradient {
            background: radial-gradient(circle at top left, #4f46e5, #4338ca);
        }
        .animate-subtle {
            animation: floating 4s ease-in-out infinite;
        }
        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .input-focus-ring:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        .animate-shake {
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
    </style>
</head>
<body class="bg-white min-h-screen flex flex-col lg:flex-row">

    <!-- Left Panel: Brand Experience -->
    <div class="hidden lg:flex lg:w-[55%] login-gradient relative items-center justify-center p-16">
        <div class="absolute top-0 left-0 w-full h-full opacity-20">
            <div class="absolute top-20 left-20 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-indigo-400 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative z-10 text-center text-white">
            <div class="mb-10 animate-subtle inline-block">
                <div class="w-24 h-24 bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 flex items-center justify-center shadow-2xl">
                    <i class="fas fa-graduation-cap text-5xl"></i>
                </div>
            </div>
            <h1 class="text-6xl font-black tracking-tighter mb-4">E-RAPORT</h1>
            <p class="text-indigo-100 text-xl font-medium max-w-md mx-auto leading-relaxed opacity-80">
                Solusi digital penilaian masa depan untuk siswa unggul SMKN 1 Sungailiat.
            </p>
            
            <div class="mt-16 flex items-center justify-center space-x-8">
                <div class="text-center">
                    <p class="text-3xl font-bold">2026</p>
                    <p class="text-xs font-bold text-indigo-300 uppercase tracking-widest">Tahun Ajaran</p>
                </div>
                <div class="h-10 w-px bg-white/20"></div>
                <div class="text-center">
                    <p class="text-3xl font-bold">100%</p>
                    <p class="text-xs font-bold text-indigo-300 uppercase tracking-widest">Digitalisasi</p>
                </div>
            </div>
        </div>

        <div class="absolute bottom-12 text-center w-full">
            <p class="text-indigo-300 text-[10px] font-bold uppercase tracking-[0.4em]">TIM IT SMKN 1 SUNGAILIAT &copy; 2026</p>
        </div>
    </div>

    <!-- Right Panel: Auth Container -->
    <div class="w-full lg:w-[45%] min-h-screen flex items-center justify-center p-4 sm:p-8 relative bg-slate-50 lg:bg-white overflow-y-auto">
        <!-- Floating shapes for mobile -->
        <div class="lg:hidden absolute top-0 left-0 w-full h-48 login-gradient -z-0 rounded-b-[40px]"></div>

        <div class="w-full max-w-[400px] relative z-10 my-auto">
            <!-- Mobile Header -->
            <div class="lg:hidden text-center mb-8 pt-4">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-xl mx-auto flex items-center justify-center mb-4">
                    <i class="fas fa-graduation-cap text-indigo-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-black text-white tracking-tight">E-RAPORT</h2>
            </div>

            <!-- Form Card -->
            <div class="bg-white p-6 sm:p-10 rounded-[32px] shadow-2xl shadow-slate-200/50 lg:shadow-none border border-slate-100 lg:border-none">
                <div class="mb-8 text-center lg:text-left">
                    <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Masuk</h3>
                    <p class="text-slate-400 text-sm font-medium">Gunakan kredensial (NISN/NIP) Anda.</p>
                </div>

                @if($errors->any())
                <div class="mb-6 animate-shake">
                    <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-xs font-bold border-l-4 border-red-500 flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        {{ $errors->first() }}
                    </div>
                </div>
                @endif

                @if(session('success'))
                <div class="mb-6">
                    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-2xl text-xs font-bold border-l-4 border-emerald-500 flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        {{ session('success') }}
                    </div>
                </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest ml-1" for="login">ID Pengguna</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                <i class="fas fa-id-card text-sm"></i>
                            </div>
                            <input type="text" name="login" id="login" required autofocus
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 lg:bg-white border-2 border-slate-100 lg:border-slate-200 rounded-2xl text-sm font-bold text-slate-700 placeholder:text-slate-400 focus:border-indigo-600 focus:bg-white outline-none transition-all input-focus-ring"
                                placeholder="NISN / NIP / Email">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest" for="password">Kata Sandi</label>
                            <a href="javascript:void(0)" onclick="openModal()" class="text-[11px] font-black text-indigo-600 hover:text-indigo-800 transition-colors">Lupa?</a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                <i class="fas fa-lock text-sm"></i>
                            </div>
                            <input type="password" name="password" id="password" required
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 lg:bg-white border-2 border-slate-100 lg:border-slate-200 rounded-2xl text-sm font-bold text-slate-700 placeholder:text-slate-400 focus:border-indigo-600 focus:bg-white outline-none transition-all input-focus-ring"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center px-1">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-5 h-5 rounded-lg border-2 border-slate-200 text-indigo-600 focus:ring-0 transition-all cursor-pointer">
                            <span class="ml-3 text-[13px] font-bold text-slate-500 group-hover:text-slate-700 transition-colors">Ingat Saya</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 rounded-2xl shadow-xl shadow-indigo-200 hover:shadow-indigo-300 transition-all active:scale-[0.98] flex items-center justify-center space-x-2">
                        <span>Masuk ke Sistem</span>
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tidy Modal Lupa Password -->
    <div id="forgotModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-md hidden items-center justify-center z-50 p-4 sm:p-6">
        <div class="bg-white rounded-[40px] p-8 sm:p-10 w-full max-w-sm shadow-2xl">
            <div class="w-20 h-20 bg-amber-50 rounded-3xl flex items-center justify-center text-amber-500 text-3xl mb-8 mx-auto">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 text-center mb-3">Reset Akses</h3>
            <p class="text-slate-500 text-center text-sm font-medium mb-8 leading-relaxed">Admin akan memverifikasi ID Anda untuk melakukan reset password.</p>
            <form action="{{ route('forgot-password') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="username" placeholder="Masukkan NISN / NIP" required
                        class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl text-sm font-bold focus:border-amber-500 outline-none transition-all">
                    <div class="flex gap-4">
                        <button type="button" onclick="closeModal()" class="flex-1 py-4 text-sm font-bold text-slate-400 hover:text-slate-600 transition">Batal</button>
                        <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl text-sm font-extrabold shadow-lg hover:bg-black transition active:scale-95">Kirim</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            const modal = document.getElementById('forgotModal');
            modal.style.display = 'flex';
            modal.classList.add('animate-in', 'fade-in', 'duration-300');
        }
        function closeModal() {
            document.getElementById('forgotModal').style.display = 'none';
        }
    </script>

</body>
</html>
