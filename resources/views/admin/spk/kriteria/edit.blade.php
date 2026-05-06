<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kriteria SPK - E-Raport</title>
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
            <a href="{{ route('admin.kriteria.index') }}" class="inline-flex items-center text-slate-400 hover:text-indigo-600 mb-6 transition font-semibold group">
                <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Daftar Kriteria
            </a>

            <!-- Card Form -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
                <div class="bg-indigo-900 p-8 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-2xl font-bold tracking-tight">Edit Kriteria SAW</h2>
                        <p class="text-indigo-300 text-sm mt-1">Memperbarui kriteria <strong>{{ $kriteria->kode }} - {{ $kriteria->nama }}</strong>.</p>
                    </div>
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full text-9xl flex items-center justify-center opacity-20">
                        <i class="fas fa-edit"></i>
                    </div>
                </div>

                <form action="{{ route('admin.kriteria.update', $kriteria->id) }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Kriteria -->
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Kode Kriteria</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <input type="text" name="kode" value="{{ old('kode', $kriteria->kode) }}" 
                                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none uppercase" required>
                            </div>
                            @error('kode') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nama Kriteria -->
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Nama Kriteria</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-font"></i>
                                </span>
                                <input type="text" name="nama" value="{{ old('nama', $kriteria->nama) }}" 
                                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" required>
                            </div>
                            @error('nama') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Bobot -->
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Bobot (0 - 1)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-percentage"></i>
                                </span>
                                <input type="number" name="bobot" step="0.01" min="0" max="1" value="{{ old('bobot', $kriteria->bobot) }}" 
                                       class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none" required>
                            </div>
                            @error('bobot') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Jenis Kriteria -->
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Jenis Kriteria</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fas fa-sliders"></i>
                                </span>
                                <select name="jenis" class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none appearance-none cursor-pointer">
                                    <option value="benefit" {{ $kriteria->jenis == 'benefit' ? 'selected' : '' }}>Benefit (Makin Besar Makin Baik)</option>
                                    <option value="cost" {{ $kriteria->jenis == 'cost' ? 'selected' : '' }}>Cost (Makin Kecil Makin Baik)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('jenis') <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-indigo-200 transition-all active:scale-[0.98] flex items-center justify-center space-x-2">
                            <i class="fas fa-check-circle"></i>
                            <span>Update Kriteria</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
