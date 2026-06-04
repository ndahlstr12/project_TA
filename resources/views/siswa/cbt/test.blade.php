@extends('layouts.admin')

@section('title', 'Ujian Sedang Berlangsung')

@section('content')
<div class="max-w-4xl mx-auto space-y-8" id="exam-container">
    <!-- Header with Timer -->
    <div class="sticky top-4 z-30 flex items-center justify-between p-6 bg-white dark:bg-surface-800 rounded-2xl shadow-xl border border-slate-100 dark:border-white/5 backdrop-blur-md bg-opacity-90">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-600 flex items-center justify-center text-white">
                <i data-lucide="timer" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sisa Waktu</p>
                <p id="timer" class="text-xl font-black text-slate-800 dark:text-white tabular-nums">00:00:00</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Progress</p>
                <p class="text-xs font-bold text-slate-800 dark:text-white"><span id="answered-count">0</span> / {{ $soals->count() }} Terjawab</p>
            </div>
            <button onclick="confirmSubmit()" class="px-6 py-3 bg-rose-600 text-white text-[10px] font-bold rounded-xl uppercase tracking-widest shadow-lg shadow-rose-600/20 hover:bg-rose-700 transition-all">
                Selesai Ujian
            </button>
        </div>
    </div>

    <form id="exam-form" action="{{ route('siswa.cbt.submit', $ujian->id) }}" method="POST">
        @csrf
        <div class="space-y-6">
            @foreach($soals as $index => $soal)
            <div class="card-soft p-8 md:p-10 question-card" id="q-{{ $soal->id }}">
                <div class="flex gap-6">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400 font-bold shrink-0">
                        {{ $index + 1 }}
                    </div>
                    <div class="space-y-8 flex-1">
                        <p class="text-lg font-medium text-slate-800 dark:text-slate-200 leading-relaxed">
                            {!! $soal->pertanyaan !!}
                        </p>

                        <div class="grid grid-cols-1 gap-3">
                            @foreach(['A', 'B', 'C', 'D', 'E'] as $opt)
                                @php $key = 'opsi_' . strtolower($opt); @endphp
                                @if($soal->$key)
                                <label class="flex items-center p-5 rounded-2xl border-2 border-slate-50 dark:border-white/5 hover:border-rose-500/30 cursor-pointer transition-all group relative has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50/30 dark:has-[:checked]:bg-rose-500/5">
                                    <input type="radio" name="answers[{{ $soal->id }}]" value="{{ $opt }}" class="hidden peer" onchange="updateProgress()">
                                    <div class="w-8 h-8 rounded-lg border-2 border-slate-200 dark:border-white/10 flex items-center justify-center text-xs font-bold text-slate-400 peer-checked:border-rose-500 peer-checked:bg-rose-500 peer-checked:text-white transition-all mr-4">
                                        {{ $opt }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400 peer-checked:text-slate-900 dark:peer-checked:text-white transition-all">
                                        {{ $soal->$key }}
                                    </span>
                                </label>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Timer Logic
    let timeLeft = {{ $ujian->durasi * 60 }};
    const timerElement = document.getElementById('timer');
    
    function updateTimer() {
        const hours = Math.floor(timeLeft / 3600);
        const minutes = Math.floor((timeLeft % 3600) / 60);
        const seconds = timeLeft % 60;
        
        timerElement.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            document.getElementById('exam-form').submit();
        } else {
            timeLeft--;
            setTimeout(updateTimer, 1000);
        }
    }
    
    updateTimer();

    function updateProgress() {
        const answered = document.querySelectorAll('input[type="radio"]:checked').length;
        document.getElementById('answered-count').textContent = answered;
    }

    function confirmSubmit() {
        if (confirm('Apakah Anda yakin ingin mengakhiri ujian ini?')) {
            document.getElementById('exam-form').submit();
        }
    }

    // Prevent closing/refreshing
    window.onbeforeunload = function() {
        return "Ujian sedang berlangsung. Apakah Anda yakin ingin meninggalkan halaman ini?";
    };

    document.getElementById('exam-form').onsubmit = function() {
        window.onbeforeunload = null;
    };
</script>
@endpush
@endsection
