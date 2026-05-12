@extends('layouts.admin')

@section('title', 'Daftar Kelas')
@section('page_title', 'Manajemen Data Kelas')

@section('content')
<div class="space-y-8">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-navy-900 dark:text-white">Daftar Kelas</h2>
            <p class="text-sm text-navy-400 mt-1 uppercase tracking-widest font-bold">Total Terdaftar: {{ $kelasList->total() }} Kelas</p>
        </div>
        <a href="{{ route('admin.kelas.create') }}" class="px-6 py-3 bg-navy-950 dark:bg-white text-white dark:text-navy-950 text-[10px] font-bold rounded-xl uppercase tracking-widest hover:opacity-90 transition-all shadow-xl flex items-center gap-2">
            <i class="ti ti-plus text-base"></i>
            Tambah Kelas Baru
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
            <i class="ti ti-check w-4 h-4"></i>
        </div>
        <p class="text-xs font-bold text-emerald-600 tracking-tight">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Content Card -->
    <div class="card-pro overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-navy-50/50 dark:bg-white/5 border-b border-navy-100 dark:border-white/5">
                        <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest">ID</th>
                        <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest">Nama Kelas</th>
                        <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest text-center">Tingkat</th>
                        <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest text-center">Jurusan</th>
                        <th class="px-8 py-5 text-[10px] font-extrabold text-navy-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-100 dark:divide-white/5">
                    @forelse($kelasList as $kelas)
                    <tr class="hover:bg-navy-50/30 dark:hover:bg-white/5 transition-colors group">
                        <td class="px-8 py-5 text-xs font-bold text-navy-300">#{{ $kelas->id }}</td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-9 h-9 rounded-xl bg-blue-500 text-white flex items-center justify-center text-xs font-black shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform">
                                    {{ substr($kelas->nama_kelas, 0, 1) }}
                                </div>
                                <span class="text-sm font-bold text-navy-900 dark:text-white">{{ $kelas->nama_kelas }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-3 py-1 bg-navy-50 dark:bg-white/5 text-[10px] font-black text-navy-500 dark:text-navy-400 uppercase rounded-lg border border-navy-100 dark:border-white/10">
                                {{ $kelas->tingkat }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="text-xs font-bold text-navy-500 dark:text-navy-400">{{ $kelas->jurusan }}</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.kelas.edit', $kelas->id) }}" class="p-2 text-navy-400 hover:text-blue-500 transition-colors">
                                    <i class="ti ti-edit text-lg"></i>
                                </a>
                                <form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini? Semua data terkait mungkin terpengaruh.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-navy-400 hover:text-rose-500 transition-colors">
                                        <i class="ti ti-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-navy-50 dark:bg-white/5 rounded-full flex items-center justify-center text-navy-200 mb-4">
                                    <i class="ti ti-category text-4xl"></i>
                                </div>
                                <h4 class="text-sm font-bold text-navy-900 dark:text-white">Belum Ada Data Kelas</h4>
                                <p class="text-xs text-navy-400 mt-1">Silakan tambah kelas baru untuk mulai mengelola data.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($kelasList->hasPages())
        <div class="p-6 bg-navy-50/30 dark:bg-white/5 border-t border-navy-100 dark:border-white/5">
            {{ $kelasList->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
