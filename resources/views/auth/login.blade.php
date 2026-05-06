<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Raport SMKN 1 Sungailiat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-indigo-900 via-blue-900 to-indigo-800 flex items-center justify-center min-h-screen p-6 font-sans">

    <div class="bg-white/95 backdrop-blur-sm p-10 rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-100 rounded-full mb-4 shadow-inner">
                <i class="fas fa-graduation-cap text-indigo-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">E-RAPORT</h1>
            <p class="text-indigo-600 font-medium uppercase tracking-widest text-xs mt-1">SMKN 1 Sungailiat</p>
            <div class="w-16 h-1 bg-indigo-500 mx-auto mt-4 rounded-full"></div>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex items-center shadow-sm">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <span class="text-sm font-medium">{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Tipe Login Selector -->
            <div class="flex p-1 bg-gray-100 rounded-xl mb-6">
                <button type="button" id="type-admin" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all bg-white shadow-sm text-indigo-600">
                    ADMIN
                </button>
                <button type="button" id="type-guru" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all text-gray-500">
                    GURU
                </button>
                <button type="button" id="type-siswa" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all text-gray-500">
                    SISWA/ORTU
                </button>
            </div>

            <div id="login-container">
                <label class="block text-gray-700 text-sm font-semibold mb-2 ml-1" id="login-label" for="login">
                    Alamat Email
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-envelope" id="login-icon"></i>
                    </span>
                    <input class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm" 
                           id="login" name="login" type="text" placeholder="admin@smkn1sungailiat.sch.id" required autofocus>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2 ml-1" for="password">
                    Kata Sandi
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm" 
                           id="password" name="password" type="password" placeholder="••••••••" required>
                </div>
            </div>
            
            <div class="flex items-center justify-between px-1">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                </label>
                <a href="#" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">Lupa password?</a>
            </div>

            <div>
                <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-all active:scale-95" type="submit">
                    Masuk ke Sistem
                </button>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const btns = {
                    admin: document.getElementById('type-admin'),
                    guru: document.getElementById('type-guru'),
                    siswa: document.getElementById('type-siswa')
                };
                const label = document.getElementById('login-label');
                const input = document.getElementById('login');
                const icon = document.getElementById('login-icon');

                function setActive(type) {
                    Object.keys(btns).forEach(k => {
                        btns[k].classList.remove('bg-white', 'shadow-sm', 'text-indigo-600');
                        btns[k].classList.add('text-gray-500');
                    });
                    btns[type].classList.add('bg-white', 'shadow-sm', 'text-indigo-600');
                    btns[type].classList.remove('text-gray-500');

                    if (type === 'admin') {
                        label.innerText = 'Alamat Email';
                        input.placeholder = 'admin@smkn1sungailiat.sch.id';
                        icon.className = 'fas fa-envelope';
                    } else if (type === 'guru') {
                        label.innerText = 'Nomor Induk Pegawai (NIP)';
                        input.placeholder = 'Contoh: 1980...';
                        icon.className = 'fas fa-id-badge';
                    } else {
                        label.innerText = 'NISN (Siswa / Anak)';
                        input.placeholder = 'Contoh: 00123...';
                        icon.className = 'fas fa-id-card';
                    }
                }

                btns.admin.addEventListener('click', () => setActive('admin'));
                btns.guru.addEventListener('click', () => setActive('guru'));
                btns.siswa.addEventListener('click', () => setActive('siswa'));
            });
        </script>

        <div class="mt-10 text-center border-t border-gray-100 pt-8">
            <p class="text-gray-400 text-xs font-medium uppercase tracking-tighter">&copy; 2026 TIM IT SMKN 1 SUNGAILIAT</p>
        </div>
    </div>
</body>
</html>
