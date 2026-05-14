<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kriteria SPK - E-Raport</title>
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

            <!-- Card Detail -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
                <div class="bg-indigo-900 p-8 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-2xl font-bold tracking-tight">Detail Kriteria SAW</h2>
                        <p class="text-indigo-300 text-sm mt-1">Informasi lengkap kriteria <strong>{{ $kriteria->kode }} - {{ $kriteria->nama }}</strong>.</p>
                    </div>
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full text-9xl flex items-center justify-center opacity-20">
                        <i class="fas fa-info-circle"></i>
                    </div>
                </div>

                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Kode Kriteria</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-indigo-600 border border-slate-100">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <span class="text-lg font-black text-slate-800">{{ $kriteria->kode }}</span>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Atribut</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-indigo-600 border border-slate-100">
                                    <i class="fas fa-font"></i>
                                </div>
                                <span class="text-lg font-bold text-slate-800">{{ $kriteria->nama }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Alokasi Bobot</p>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-indigo-600 border border-slate-100">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div>
                                    <span class="text-lg font-black text-slate-800">{{ $kriteria->bobot * 100 }}%</span>
                                    <div class="w-32 h-1.5 bg-slate-100 rounded-full mt-1 overflow-hidden">
                                        <div class="h-full bg-indigo-600" style="width: {{ $kriteria->bobot * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jenis Kriteria</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-indigo-600 border border-slate-100">
                                    <i class="fas fa-sliders"></i>
                                </div>
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest
                                    {{ $kriteria->jenis == 'benefit' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                    {{ $kriteria->jenis }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-amber-500"></i>
                            Keterangan Logika
                        </h4>
                        <p class="text-sm text-slate-500 leading-relaxed italic">
                            @if($kriteria->jenis == 'benefit')
                                Kriteria ini bersifat <strong>Benefit</strong>, yang berarti semakin besar nilai yang diperoleh siswa pada parameter ini, maka akan semakin baik pengaruhnya terhadap peringkat akhir.
                            @else
                                Kriteria ini bersifat <strong>Cost</strong>, yang berarti semakin kecil nilai yang diperoleh siswa pada parameter ini, maka akan semakin baik pengaruhnya terhadap peringkat akhir.
                            @endif
                        </p>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <a href="{{ route('admin.kriteria.edit', $kriteria->id) }}" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-indigo-200 transition-all active:scale-[0.98] flex items-center justify-center space-x-2">
                            <i class="fas fa-edit"></i>
                            <span>Edit Kriteria</span>
                        </a>
                        <form action="{{ route('admin.kriteria.destroy', $kriteria->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kriteria ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-8 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold py-4 rounded-2xl transition-all active:scale-[0.98]">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
