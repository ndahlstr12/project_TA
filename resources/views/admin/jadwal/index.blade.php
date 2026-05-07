@extends('layouts.admin')

@section('title', 'Jadwal Sekolah')
@section('page_title', 'Manajemen Jadwal')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Jadwal Pelajaran</h2>
            <p class="text-slate-500 font-medium">Atur dan kelola jadwal kegiatan belajar mengajar.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="openModal('importModal')" class="inline-flex items-center justify-center space-x-2 bg-white border border-slate-200 text-slate-700 font-bold py-3 px-6 rounded-2xl shadow-sm hover:bg-slate-50 transition active:scale-95">
                <i class="fas fa-file-import text-indigo-500"></i>
                <span>Import Excel</span>
            </button>
            <button onclick="openModal('addJadwalModal')" class="inline-flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-2xl shadow-lg shadow-indigo-200 transition active:scale-95">
                <i class="fas fa-plus"></i>
                <span>Tambah Jadwal</span>
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl mb-6 flex items-center shadow-sm">
        <i class="fas fa-check-circle mr-3"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Jadwal Cards by Day -->
    @php
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    @endphp

    <div class="space-y-10">
        @foreach($days as $day)
        <div>
            <div class="flex items-center space-x-4 mb-6">
                <h3 class="text-xl font-bold text-slate-800 tracking-tight">{{ $day }}</h3>
                <div class="h-px flex-1 bg-slate-100"></div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $jadwals->where('hari', $day)->count() }} Mapel</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($jadwals->where('hari', $day) as $jadwal)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 group overflow-hidden">
                    <div class="h-2 bg-indigo-500"></div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                            </div>
                            <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="text-slate-400 hover:text-indigo-600 p-1"><i class="fas fa-edit text-xs"></i></button>
                                <button class="text-slate-400 hover:text-red-500 p-1"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                        </div>
                        
                        <h4 class="text-lg font-bold text-slate-800 mb-1 group-hover:text-indigo-600 transition">{{ $jadwal->mapel }}</h4>
                        <div class="space-y-3 mt-4">
                            <div class="flex items-center text-sm text-slate-500 font-medium">
                                <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user-tie text-xs text-slate-400"></i>
                                </div>
                                <span class="truncate">{{ $jadwal->guru }}</span>
                            </div>
                            <div class="flex items-center text-sm text-slate-500 font-medium">
                                <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-door-open text-xs text-slate-400"></i>
                                </div>
                                <span>Kelas {{ $jadwal->kelas }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-8 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                    <p class="text-slate-400 font-medium">Belum ada jadwal untuk hari {{ $day }}</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div id="addJadwalModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white rounded-[32px] w-full max-w-lg shadow-2xl my-auto overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Tambah Jadwal Baru</h3>
                <p class="text-xs text-slate-500 font-medium mt-1">Lengkapi informasi jadwal pelajaran di bawah.</p>
            </div>
            <button onclick="closeModal('addJadwalModal')" class="text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Hari</label>
                    <select name="hari" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-bold text-slate-700" required>
                        @foreach($days as $day)
                            <option value="{{ $day }}">{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-bold text-slate-700" required>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-bold text-slate-700" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Mata Pelajaran</label>
                    <input type="text" name="mapel" placeholder="Contoh: Matematika" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-bold text-slate-700" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Nama Guru</label>
                    <input type="text" name="guru" placeholder="Masukkan nama guru mapel" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-bold text-slate-700" required>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Kelas</label>
                    <input type="text" name="kelas" placeholder="Contoh: X RPL 1" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition font-bold text-slate-700" required>
                </div>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModal('addJadwalModal')" class="flex-1 py-4 text-sm font-bold text-slate-400 hover:text-slate-600 transition">Batal</button>
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-4 rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-95">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
        document.body.style.overflow = 'auto';
    }
</script>
@endsection
