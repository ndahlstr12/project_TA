@extends('layouts.admin')

@section('title', 'Jadwal Pelajaran')
@section('page_title', 'Jadwal Pelajaran')

@section('content')
<div x-data="{ modal: null }" class="space-y-12">
    
    <!-- Pro Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-base pb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter">Jadwal Pelajaran</h1>
            <p class="text-sm text-neutral-500 mt-2">Penjadwalan strategis untuk sumber daya pengajar dan alokasi ruang kelas.</p>
        </div>
        <div class="flex gap-3">
            <button @click="modal = 'import'" class="px-4 py-2 text-xs font-bold border border-base rounded-lg hover:bg-neutral-50 dark:hover:bg-white/5 transition-all">
                Impor Data
            </button>
            <button @click="modal = 'add'" class="px-5 py-2 text-xs font-bold bg-neutral-950 dark:bg-white text-white dark:text-neutral-950 rounded-lg hover:opacity-90 transition-all flex items-center gap-2">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                Buat Jadwal
            </button>
        </div>
    </div>

    <!-- Daily Registry -->
    @php $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; @endphp

    <div class="grid grid-cols-1 gap-12">
        @foreach($days as $day)
        <div class="space-y-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-neutral-900 dark:bg-white text-white dark:text-neutral-900 flex items-center justify-center font-black text-xs">
                    {{ substr($day, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-sm font-black uppercase tracking-[0.2em] text-neutral-900 dark:text-white">{{ $day }}</h3>
                    <p class="text-[9px] font-bold text-neutral-400 uppercase tracking-widest mt-0.5">{{ $jadwals->where('hari', $day)->count() }} Sesi Pelajaran</p>
                </div>
                <div class="h-px flex-1 bg-neutral-100 dark:bg-white/5"></div>
            </div>

            <div class="card-pro overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-neutral-50/50 dark:bg-white/5 border-b border-base text-[9px] font-black text-neutral-400 uppercase tracking-[0.2em]">
                                <th class="px-8 py-4 w-40">Waktu / Jam</th>
                                <th class="px-8 py-4">Mata Pelajaran</th>
                                <th class="px-8 py-4">Tenaga Pengajar</th>
                                <th class="px-8 py-4">Ruang / Kelas</th>
                                <th class="px-8 py-4 text-right">Manajemen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-base">
                            @forelse($jadwals->where('hari', $day)->sortBy('jam_mulai') as $jadwal)
                            <tr class="hover:bg-neutral-50/30 dark:hover:bg-white/5 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-accent"></div>
                                        <span class="text-xs font-bold text-neutral-800 dark:text-neutral-200">
                                            {{ $jadwal->jam_mulai }} — {{ $jadwal->jam_selesai }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <p class="text-xs font-bold text-neutral-900 dark:text-white tracking-tight">{{ $jadwal->mapel->nama_mapel ?? 'N/A' }}</p>
                                    <p class="text-[9px] text-neutral-400 font-medium uppercase mt-1 tracking-tighter italic">Kurikulum Nasional</p>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-neutral-100 dark:bg-white/5 flex items-center justify-center text-neutral-400">
                                            <i data-lucide="user-check" class="w-4 h-4"></i>
                                        </div>
                                        <span class="text-xs font-bold text-neutral-600 dark:text-neutral-400">{{ $jadwal->guru->nama ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 bg-neutral-100 dark:bg-white/5 rounded-md text-[10px] font-black text-neutral-500 dark:text-neutral-400 uppercase border border-base">
                                        {{ $jadwal->kelas->nama_kelas ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                                        <button class="p-2 text-neutral-400 hover:text-neutral-900 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-white/5 rounded-lg transition-colors">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-colors">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 opacity-20">
                                        <i data-lucide="calendar-x" class="w-8 h-8"></i>
                                        <p class="text-[10px] font-bold uppercase tracking-widest">Belum ada agenda belajar</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Add Schedule Modal -->
    <div x-show="modal === 'add'" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-6">
        <div @click="modal = null" class="absolute inset-0 bg-neutral-950/20 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-surface-800 border border-base rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="p-6 border-b border-base flex items-center justify-between">
                <h3 class="font-bold tracking-tight text-neutral-900 dark:text-white">Buat Jadwal Baru</h3>
                <button @click="modal = null" class="text-neutral-400 hover:text-neutral-900"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 space-y-2">
                        <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Penugasan Hari</label>
                        <select name="hari" class="w-full px-4 py-2.5 bg-neutral-50 dark:bg-surface-900 border border-base rounded-lg text-sm font-semibold focus:ring-2 focus:ring-accent/10 transition-all outline-none" required>
                            @foreach($days as $day) <option value="{{ $day }}">{{ $day }}</option> @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="w-full px-4 py-2.5 bg-neutral-50 dark:bg-surface-900 border border-base rounded-lg text-sm font-semibold outline-none" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="w-full px-4 py-2.5 bg-neutral-50 dark:bg-surface-900 border border-base rounded-lg text-sm font-semibold outline-none" required>
                    </div>
                    <div class="col-span-2 space-y-2">
                        <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Mata Pelajaran</label>
                        <input type="text" name="mapel_nama" list="mapel_list" placeholder="Ketik nama mata pelajaran..." class="w-full px-4 py-2.5 bg-neutral-50 dark:bg-surface-900 border border-base rounded-lg text-sm font-semibold outline-none focus:ring-2 focus:ring-accent/10 transition-all" required>
                        <datalist id="mapel_list">
                            @foreach($mapels as $mapel)
                                <option value="{{ $mapel->nama_mapel }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-span-2 space-y-2">
                        <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Guru Pengampu</label>
                        <select name="guru_id" class="w-full px-4 py-2.5 bg-neutral-50 dark:bg-surface-900 border border-base rounded-lg text-sm font-semibold outline-none" required>
                            <option value="" disabled selected>Pilih Guru</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2 space-y-2">
                        <label class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest ml-1">Ruang / Kelas</label>
                        <select name="kelas_id" class="w-full px-4 py-2.5 bg-neutral-50 dark:bg-surface-900 border border-base rounded-lg text-sm font-semibold outline-none" required>
                            <option value="" disabled selected>Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="modal = null" class="flex-1 py-3 text-xs font-bold text-neutral-400 hover:text-neutral-900 transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-neutral-950 dark:bg-white text-white dark:text-neutral-950 rounded-xl text-xs font-bold shadow-lg transition-all active:scale-95">Verifikasi & Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Import Modal -->
    <div x-show="modal === 'import'" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-6">
        <div @click="modal = null" class="absolute inset-0 bg-neutral-950/20 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-md bg-white dark:bg-surface-800 border border-base rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="p-6 border-b border-base flex items-center justify-between">
                <h3 class="font-bold tracking-tight text-navy-900 dark:text-white">Impor Jadwal (CSV)</h3>
                <button @click="modal = null" class="text-neutral-400 hover:text-neutral-900"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form action="{{ route('admin.jadwal.import') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl">
                        <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase leading-relaxed">
                            Format CSV: hari, jam_mulai, jam_selesai, mapel, guru, kelas
                        </p>
                    </div>
                    <div class="relative group">
                        <input type="file" name="file" accept=".csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                        <div class="border-2 border-dashed border-base rounded-2xl p-8 text-center group-hover:border-navy-400 transition-all">
                            <i data-lucide="upload-cloud" class="w-8 h-8 mx-auto text-neutral-300 group-hover:text-navy-500 transition-colors mb-2"></i>
                            <p class="text-xs font-bold text-neutral-500 uppercase tracking-widest">Pilih File CSV</p>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="modal = null" class="flex-1 py-3.5 text-xs font-bold text-neutral-400">Batal</button>
                    <button type="submit" class="flex-1 py-3.5 bg-navy-950 dark:bg-white text-white dark:text-navy-950 rounded-xl text-xs font-black uppercase tracking-widest shadow-xl active:scale-95">Mulai Impor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Lucide trigger for dynamic content
    document.addEventListener('alpine:init', () => {
        lucide.createIcons();
    });
</script>
@endpush
