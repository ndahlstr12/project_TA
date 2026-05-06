<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - E-Raport SMKN 1 Sungailiat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-indigo-900 via-blue-900 to-indigo-800 flex items-center justify-center min-h-screen p-6 font-sans">

    <div class="bg-white/95 backdrop-blur-sm p-8 md:p-10 rounded-3xl shadow-2xl w-full max-w-lg transform transition-all">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-2xl mb-4 shadow-inner">
                <i class="fas fa-user-plus text-indigo-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Daftar Akun Baru</h1>
            <p class="text-indigo-600 font-medium uppercase tracking-widest text-[10px] mt-1">E-Raport SMKN 1 Sungailiat</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 shadow-sm">
                <ul class="text-xs font-medium space-y-1">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-circle text-[6px] mr-2"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" class="space-y-5">
            @csrf
            
            <!-- Pilihan Role -->
            <div class="flex p-1 bg-gray-100 rounded-xl mb-6">
                <button type="button" id="btn-siswa" onclick="setRole('siswa')" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all bg-white shadow-sm text-indigo-600">
                    Siswa
                </button>
                <button type="button" id="btn-guru" onclick="setRole('guru')" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all text-gray-500">
                    Guru / Wali Kelas
                </button>
            </div>
            <input type="hidden" name="role" id="role-input" value="siswa">

            <!-- ID Number (NISN / NIP) -->
            <div>
                <label class="block text-gray-700 text-xs font-bold mb-2 ml-1" id="id-label" for="id_number">NISN Siswa</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-id-card"></i>
                    </span>
                    <input class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all" 
                           id="id_number" name="id_number" type="text" placeholder="Masukkan 10 digit NISN" value="{{ old('id_number') }}" required autofocus>
                </div>
                <p class="text-[10px] text-gray-400 mt-1 ml-1" id="id-hint">*NISN digunakan untuk validasi data siswa.</p>
            </div>

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-gray-700 text-xs font-bold mb-2 ml-1" for="name">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-user"></i>
                    </span>
                    <input class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all" 
                           id="name" name="name" type="text" placeholder="Nama Lengkap Anda" value="{{ old('name') }}" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Password -->
                <div>
                    <label class="block text-gray-700 text-xs font-bold mb-2 ml-1" for="password">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-lock"></i></span>
                        <input class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none" 
                               id="password" name="password" type="password" placeholder="••••••••" required>
                    </div>
                </div>
                <!-- Konfirmasi Password -->
                <div>
                    <label class="block text-gray-700 text-xs font-bold mb-2 ml-1" for="password_confirmation">Konfirmasi Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-check-double"></i></span>
                        <input class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none" 
                               id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••" required>
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-95" type="submit">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <script>
            function setRole(role) {
                const btnSiswa = document.getElementById('btn-siswa');
                const btnGuru = document.getElementById('btn-guru');
                const roleInput = document.getElementById('role-input');
                const idLabel = document.getElementById('id-label');
                const idInput = document.getElementById('id_number');
                const idHint = document.getElementById('id-hint');

                roleInput.value = role;

                if(role === 'siswa') {
                    btnSiswa.className = 'flex-1 py-2 text-xs font-bold rounded-lg transition-all bg-white shadow-sm text-indigo-600';
                    btnGuru.className = 'flex-1 py-2 text-xs font-bold rounded-lg transition-all text-gray-500';
                    idLabel.innerText = 'NISN Siswa';
                    idInput.placeholder = 'Masukkan 10 digit NISN';
                    idHint.innerText = '*NISN digunakan untuk validasi data siswa.';
                } else {
                    btnGuru.className = 'flex-1 py-2 text-xs font-bold rounded-lg transition-all bg-white shadow-sm text-indigo-600';
                    btnSiswa.className = 'flex-1 py-2 text-xs font-bold rounded-lg transition-all text-gray-500';
                    idLabel.innerText = 'NIP Guru';
                    idInput.placeholder = 'Masukkan NIP Anda';
                    idHint.innerText = '*NIP harus sudah terdaftar di Master Data oleh Admin.';
                }
            }
        </script>

        <div class="mt-8 text-center border-t border-gray-100 pt-6">
            <p class="text-sm text-gray-500">Sudah punya akun? <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">Masuk di sini</a></p>
        </div>
    </div>
</body>
</html>
