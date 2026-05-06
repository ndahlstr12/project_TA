<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna - E-Raport</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-700">

    <div class="flex min-h-screen items-center justify-center p-6">
        <div class="w-full max-w-2xl">
            <!-- Back Button -->
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-slate-400 hover:text-indigo-600 mb-6 transition font-semibold group">
                <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Daftar
            </a>

            <!-- Card Form -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
                <div class="bg-indigo-900 p-8 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-2xl font-bold tracking-tight">Edit Pengguna</h2>
                        <p class="text-indigo-300 text-sm mt-1">Memperbarui data akses untuk <strong>{{ $user->name }}</strong>.</p>
                    </div>
                    <!-- Decorative Circle -->
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full"></div>
                </div>

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Nama Lengkap</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="far fa-user"></i>
                                </span>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" required>
                            </div>
                            @error('name') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Alamat Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="far fa-envelope"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" required>
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Role / Aktor -->
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Peran / Aktor</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                                <select name="role" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none appearance-none cursor-pointer">
                                    <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                    <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru Pengajar</option>
                                    <option value="walikelas" {{ $user->role == 'walikelas' ? 'selected' : '' }}>Wali Kelas</option>
                                    <option value="orangtua" {{ $user->role == 'orangtua' ? 'selected' : '' }}>Orang Tua</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Password (Opsional) -->
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Kata Sandi Baru</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" placeholder="Kosongkan jika tidak diganti" 
                                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none">
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1 ml-1 font-medium italic">*Isi hanya jika ingin mengganti password</p>
                        </div>
                    </div>

                    <div class="pt-4 flex space-x-4">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-indigo-200 transition-all active:scale-[0.98] flex items-center justify-center space-x-2">
                            <i class="fas fa-check-circle"></i>
                            <span>Update Data</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
