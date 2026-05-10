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
                <h3 class="text-sm font-black uppercase tracking-[0.2em] text-neutral-900 dark:text-white">{{ $day }}</h3>
                <div class="h-px flex-1 bg-neutral-100 dark:bg-white/5"></div>
                <span class="text-[10px] font-bold text-neutral-400 uppercase">{{ $jadwals->where('hari', $day)->count() }} Mata Pelajaran</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse($jadwals->where('hari', $day) as $jadwal)
                <div class="card-pro group cursor-pointer hover:border-neutral-400 transition-colors">
                    <div class="p-5 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-accent uppercase tracking-widest bg-accent/5 px-2 py-1 rounded border border-accent/10">
                                {{ $jadwal->jam_mulai }} — {{ $jadwal->jam_selesai }}
                            </span>
                            <div class="flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="p-1 text-neutral-400 hover:text-neutral-900"><i data-lucide="edit-2" class="w-3 h-3"></i></button>
                                <button class="p-1 text-neutral-400 hover:text-rose-500"><i data-lucide="trash" class="w-3 h-3"></i></button>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-neutral-800 dark:text-neutral-200 tracking-tight line-clamp-1">{{ $jadwal->mapel }}</h4>
                            <p class="text-[11px] text-neutral-500 font-medium mt-1">Pengajar: {{ $jadwal->guru }}</p>
                        </div>

                        <div class="pt-4 border-t border-base flex items-center justify-between">
                            <span class="text-[10px] font-bold text-neutral-400 uppercase">Alokasi Ruang</span>
                            <span class="text-[10px] font-black text-neutral-700 dark:text-neutral-300">{{ $jadwal->kelas }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-10 rounded-xl border border-dashed border-base flex flex-col items-center justify-center gap-2 opacity-30">
                    <i data-lucide="calendar-x" class="w-5 h-5"></i>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-center">Tidak ada jadwal</p>
                </div>
                @endforelse
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
                        <input type="text" name="mapel" class="w-full px-4 py-2.5 bg-neutral-50 dark:bg-surface-900 border border-base rounded-lg text-sm font-semibold outline-none" placeholder="Contoh: Matematika" required>
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="modal = null" class="flex-1 py-3 text-xs font-bold text-neutral-400 hover:text-neutral-900 transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-neutral-950 dark:bg-white text-white dark:text-neutral-950 rounded-xl text-xs font-bold shadow-lg transition-all active:scale-95">Verifikasi & Simpan</button>
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
