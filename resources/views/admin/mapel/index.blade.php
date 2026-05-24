@extends('layouts.admin')

@section('title', 'Manajemen Mata Pelajaran')
@section('page_title', 'Data Mata Pelajaran')

@section('content')
<div x-data="{ modal: null, currentMapel: { id: '', nama_mapel: '', kode_mapel: '' } }" class="space-y-8">
    
    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-200 dark:border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                <i class="ti ti-book text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Daftar Mata Pelajaran</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Kelola data kurikulum dan mata pelajaran sekolah</p>
            </div>
        </div>
        <button @click="modal = 'add'" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-500/25 flex items-center gap-2">
            <i class="ti ti-plus"></i> Tambah Mapel
        </button>
    </div>

    <!-- Data Table -->
    <div class="card-pro overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-100 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                        <th class="px-6 py-5 w-20 text-center">No</th>
                        <th class="px-6 py-5">Kode Mapel</th>
                        <th class="px-6 py-5">Nama Mata Pelajaran</th>
                        <th class="px-6 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                    @forelse($mapels as $index => $mapel)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-5 text-center font-bold text-slate-400 text-xs">{{ $index + 1 }}</td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 font-black text-[10px] uppercase tracking-wider border border-slate-200 dark:border-white/10">
                                {{ $mapel->kode_mapel }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $mapel->nama_mapel }}</span>
                        </td>
                        <td class="px-6 py-5 text-right flex justify-end gap-2">
                            <button @click="modal = 'edit'; currentMapel = { id: '{{ $mapel->id }}', nama_mapel: '{{ $mapel->nama_mapel }}', kode_mapel: '{{ $mapel->kode_mapel }}' }" 
                                    class="p-2 bg-amber-500/10 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition-all">
                                <i class="ti ti-edit text-base"></i>
                            </button>
                            <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-rose-500/10 text-rose-600 hover:bg-rose-500 hover:text-white rounded-lg transition-all">
                                    <i class="ti ti-trash text-base"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i class="ti ti-book-off text-5xl mb-4"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Belum ada data mata pelajaran</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modals -->
    <!-- Add Modal -->
    <template x-if="modal === 'add'">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm" @click="modal = null"></div>
            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="p-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                    <h3 class="font-black text-sm uppercase tracking-widest text-slate-900 dark:text-white">Tambah Mata Pelajaran</h3>
                    <button @click="modal = null" class="text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors"><i class="ti ti-x text-xl"></i></button>
                </div>
                <form action="{{ route('admin.mapel.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kode Mapel</label>
                        <input type="text" name="kode_mapel" placeholder="Contoh: MAT-01" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-500/20 outline-none transition-all" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" placeholder="Contoh: Matematika" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-500/20 outline-none transition-all" required>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="modal = null" class="flex-1 py-3.5 text-xs font-black text-slate-400 uppercase tracking-widest">Batal</button>
                        <button type="submit" class="flex-1 py-3.5 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all active:scale-95">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Edit Modal -->
    <template x-if="modal === 'edit'">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm" @click="modal = null"></div>
            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="p-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                    <h3 class="font-black text-sm uppercase tracking-widest text-slate-900 dark:text-white">Edit Mata Pelajaran</h3>
                    <button @click="modal = null" class="text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors"><i class="ti ti-x text-xl"></i></button>
                </div>
                <form :action="'{{ url('admin/mapel') }}/' + currentMapel.id" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kode Mapel</label>
                        <input type="text" name="kode_mapel" x-model="currentMapel.kode_mapel" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-500/20 outline-none transition-all" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" x-model="currentMapel.nama_mapel" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-blue-500/20 outline-none transition-all" required>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="modal = null" class="flex-1 py-3.5 text-xs font-black text-slate-400 uppercase tracking-widest">Batal</button>
                        <button type="submit" class="flex-1 py-3.5 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all active:scale-95">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>
@endsection
