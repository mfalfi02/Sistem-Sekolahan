<section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
    <h2 class="text-3xl font-semibold">Dashboard Siswa</h2>
    <p class="text-slate-300 mt-2">Lihat progres belajar dan kehadiran Anda.</p>
    <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15 mt-4 inline-block">
        Lihat Dashboard Umum
    </a>
</section>

<section class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
    <h3 class="text-2xl font-semibold mb-6">Statistik Pribadi</h3>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($cards as $card)
            <div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-center">
                <div class="text-3xl mb-2">{{ $card['icon'] }}</div>
                <p class="text-xl font-bold">{{ $card['value'] }}</p>
                <p class="text-sm text-slate-400 mt-1">{{ $card['label'] }}</p>
            </div>
        @endforeach
    </div>
    @if (isset($avgGrade))
        <div class="mt-8 p-6 rounded-3xl bg-gradient-to-r from-teal-500/20 to-emerald-500/20 border border-teal-300/30">
            <p class="text-2xl font-semibold text-teal-200">Rata-rata Nilai: {{ number_format($avgGrade, 2) }}</p>
        </div>
    @endif
</section>

<section class="grid gap-6 lg:grid-cols-2">
    <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
        <h3 class="text-xl font-semibold">Menuju Target</h3>
        <div class="mt-6 space-y-4">
            <div class="flex justify-between items-center p-4 rounded-xl bg-white/5">
                <span>Nilai Minimal KKM (75)</span>
                <span class="font-semibold text-teal-300">{{ isset($avgGrade) && $avgGrade >= 75 ? 'Tercapai' : number_format($avgGrade ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between items-center p-4 rounded-xl bg-white/5">
                <span>Kehadiran &gt; 90%</span>
                <span class="font-semibold text-emerald-300">{{ isset($cards[0]['value']) && (int) str_replace('%', '', $cards[0]['value']) > 90 ? 'Tercapai' : 'Belum' }}</span>
            </div>
        </div>
    </div>
    <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
        <h3 class="text-xl font-semibold">Tips Belajar</h3>
        <ul class="mt-4 space-y-2 text-slate-300">
            <li>Review catatan harian</li>
            <li>Latihan soal setiap hari</li>
            <li>Bertanya kepada guru</li>
        </ul>
    </div>
</section>
