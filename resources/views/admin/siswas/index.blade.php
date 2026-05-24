@extends('layouts.admin')

@section('title', 'Data Siswa')
@section('page_title', 'Registrasi Siswa')

@section('content')
<div class="space-y-10">
    
    <!-- Soft Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Registrasi Siswa</h1>
            <p class="text-sm text-slate-400 font-medium mt-1">Kelola data akademik dan identitas siswa seluruh angkatan.</p>
        </div>
        <div class="flex gap-3">
            <button @click="$refs.importModal.showModal()" class="px-5 py-3 bg-emerald-500 text-white text-[11px] font-bold rounded-2xl shadow-xl shadow-emerald-500/20 uppercase tracking-widest flex items-center gap-2 hover:bg-emerald-600 transition-all">
                <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                Import CSV
            </button>
            <a href="{{ route('admin.siswas.create') }}" class="px-6 py-3 bg-accent-600 text-white text-[11px] font-bold rounded-2xl shadow-xl shadow-accent-500/20 uppercase tracking-widest flex items-center gap-2 hover:bg-accent-700 transition-all">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                Tambah Siswa
            </a>
        </div>
    </div>

    <!-- Modal Import -->
    <dialog x-ref="importModal" class="bg-transparent backdrop:bg-navy-950/50 p-0" x-data="{ fileName: '' }">
        <div class="w-[500px] bg-white dark:bg-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl border border-white dark:border-white/5">
            <div class="p-8 border-b border-slate-50 dark:border-white/5 flex justify-between items-center bg-slate-50/50 dark:bg-white/5">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Import Data Siswa</h3>
                    <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-[0.2em] font-black">Format: NISN, Nama, Nama Kelas</p>
                </div>
                <button @click="$refs.importModal.close(); fileName = ''" class="p-3 bg-white dark:bg-surface-800 rounded-2xl text-slate-400 hover:text-rose-500 transition-all">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('admin.siswas.import') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                <div class="p-10 border-2 border-dashed border-slate-100 dark:border-white/10 rounded-3xl flex flex-col items-center justify-center bg-slate-50/30 dark:bg-white/5 group hover:border-accent-500 transition-all cursor-pointer relative">
                    <input type="file" name="file" 
                        @change="fileName = $event.target.files[0].name"
                        class="absolute inset-0 opacity-0 cursor-pointer" required>
                    <div class="w-20 h-20 bg-accent-500/10 text-accent-500 rounded-3xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                        <i data-lucide="file-spreadsheet" class="w-10 h-10" x-show="!fileName"></i>
                        <i data-lucide="check-circle" class="w-10 h-10 text-emerald-500" x-show="fileName" x-cloak></i>
                    </div>
                    <p class="text-sm font-bold text-slate-900 dark:text-white" x-text="fileName ? fileName : 'Pilih file CSV data siswa'"></p>
                    <p class="text-[10px] text-slate-400 mt-2 uppercase font-black tracking-widest" x-text="fileName ? 'File terpilih - Klik sinkronisasi untuk lanjut' : 'Klik atau seret file ke sini'"></p>
                </div>
                
                <button type="submit" class="w-full py-5 bg-accent-600 text-white text-[11px] font-bold rounded-2xl shadow-xl shadow-accent-500/20 uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:bg-accent-700 transition-all">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    Mulai Sinkronisasi
                </button>
            </form>
        </div>
    </dialog>

    @if(session('success'))
    <div class="p-5 bg-emerald-500/10 border border-emerald-500/20 rounded-3xl flex items-center gap-4">
        <div class="w-10 h-10 rounded-2xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
            <i data-lucide="check" class="w-5 h-5"></i>
        </div>
        <p class="text-xs font-bold text-emerald-600 tracking-tight">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="p-5 bg-rose-500/10 border border-rose-500/20 rounded-3xl flex items-center gap-4">
        <div class="w-10 h-10 rounded-2xl bg-rose-500 flex items-center justify-center text-white shadow-lg shadow-rose-500/20">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
        </div>
        <p class="text-xs font-bold text-rose-600 tracking-tight">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Data Surface -->
    <div class="card-soft overflow-hidden">
        <!-- Control Bar -->
        <form action="{{ route('admin.siswas.index') }}" method="GET" class="p-6 border-b border-slate-50 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="relative group w-full md:w-96">
                <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-accent-500 transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NISN atau nama..." 
                    class="w-full pl-12 pr-6 py-3 bg-slate-50 dark:bg-white/5 border border-transparent rounded-2xl text-xs font-semibold focus:bg-white dark:focus:bg-surface-900 focus:border-accent-500 outline-none transition-all">
            </div>
            
            <div class="flex items-center gap-3">
                <select name="kelas_id" onchange="this.form.submit()" class="px-4 py-3 bg-slate-50 dark:bg-white/5 border border-transparent rounded-2xl text-xs font-bold focus:bg-white outline-none transition-all cursor-pointer">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="p-3 bg-slate-50 dark:bg-white/5 rounded-2xl text-slate-400 hover:text-accent-500 transition-colors">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">
                        <th class="px-8 py-6">Profil Siswa</th>
                        <th class="px-8 py-6">Tingkatan</th>
                        <th class="px-8 py-6">Gender</th>
                        <th class="px-8 py-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                    @forelse($siswas as $siswa)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-all group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-accent-50 dark:bg-accent-600/10 flex items-center justify-center text-accent-600 border border-accent-100 dark:border-accent-600/20">
                                    <i data-lucide="user" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-tight">{{ $siswa->nama }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1.5">NISN: {{ $siswa->nisn }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1.5 bg-slate-100 dark:bg-white/5 rounded-xl text-[9px] font-black text-slate-500 uppercase tracking-tighter">{{ $siswa->kelas->nama_kelas ?? 'Belum Ada Kelas' }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full {{ $siswa->jenis_kelamin == 'L' ? 'bg-blue-400' : 'bg-rose-400' }}"></div>
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-400">
                                    {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                                <a href="{{ route('admin.siswas.edit', $siswa->id) }}" class="p-2.5 bg-white dark:bg-surface-800 rounded-xl text-slate-400 hover:text-accent-600 shadow-sm border border-slate-100 dark:border-white/5 transition-all">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.siswas.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Arsipkan data ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2.5 bg-white dark:bg-surface-800 rounded-xl text-slate-400 hover:text-rose-500 shadow-sm border border-slate-100 dark:border-white/5 transition-all">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                    <i data-lucide="database-zap" class="w-10 h-10"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-300 uppercase tracking-widest">Tidak ada data ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="p-8 border-t border-slate-50 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-50/30 dark:bg-white/5">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Entitas &bull; {{ $siswas->total() }} Siswa Terdaftar</p>
            <div class="pagination-soft">
                {{ $siswas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
