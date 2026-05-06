@extends('layouts.admin')

@section('title', 'Kriteria & Bobot')
@section('page_title', 'Sistem Keputusan (SAW)')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Kriteria & Bobot SAW</h2>
            <p class="text-slate-500 font-medium italic">Atur bobot kriteria untuk menentukan peringkat siswa terbaik.</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="bg-indigo-50 border border-indigo-100 px-6 py-3 rounded-2xl shadow-sm">
                <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest">Total Bobot</p>
                <p class="text-xl font-black text-indigo-600">{{ $totalBobot * 100 }}%</p>
            </div>
            <a href="{{ route('admin.kriteria.create') }}" class="inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-95">
                <i class="fas fa-plus"></i>
                <span>Tambah Kriteria</span>
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl mb-6 flex items-center shadow-sm">
        <i class="fas fa-check-circle mr-3"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if($totalBobot != 1 && $totalBobot != 100)
    <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-700 p-4 rounded-xl mb-6 flex items-center shadow-sm">
        <i class="fas fa-exclamation-triangle mr-3"></i>
        <span class="font-medium text-sm text-amber-800 italic">Peringatan: Total bobot saat ini {{ $totalBobot * 100 }}%. Idealnya total bobot harus berjumlah 100%.</span>
    </div>
    @endif

    <!-- Criteria Table in Card -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 text-[11px] font-bold uppercase tracking-[0.2em]">
                        <th class="px-8 py-5">Kode</th>
                        <th class="px-8 py-5">Nama Kriteria</th>
                        <th class="px-8 py-5">Bobot</th>
                        <th class="px-8 py-5 text-center">Jenis</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($kriterias as $k)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <span class="bg-indigo-50 text-indigo-600 font-bold px-3 py-1 rounded-lg text-sm">{{ $k->kode }}</span>
                        </td>
                        <td class="px-8 py-5 font-bold text-slate-700">{{ $k->nama }}</td>
                        <td class="px-8 py-5">
                            <div class="flex items-center space-x-3">
                                <span class="text-slate-600 font-semibold">{{ $k->bobot * 100 }}%</span>
                                <div class="w-24 h-2 bg-slate-100 rounded-full overflow-hidden hidden sm:block">
                                    <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $k->bobot * 100 }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if($k->jenis == 'benefit')
                            <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-tighter">Benefit</span>
                            @else
                            <span class="bg-orange-50 text-orange-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-tighter">Cost</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-right space-x-4">
                            <a href="{{ route('admin.kriteria.edit', $k->id) }}" class="text-slate-400 hover:text-indigo-600 transition">
                                <i class="far fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.kriteria.destroy', $k->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kriteria ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-red-500 transition">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
