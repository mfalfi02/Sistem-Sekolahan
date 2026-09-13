@php
    $icon = function ($name, $class = 'h-5 w-5') {
        $paths = [
            'home' => '<path d="m3 10.5 9-7 9 7"/><path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/>',
            'file' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h5"/>',
            'chart' => '<path d="M3 3v18h18"/><path d="M7 16V9"/><path d="M12 16V5"/><path d="M17 16v-3"/>',
            'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9"/><path d="M16 3.1a4 4 0 0 1 0 7.8"/>',
            'school' => '<path d="m3 10 9-6 9 6"/><path d="M5 10v9"/><path d="M19 10v9"/><path d="M3 19h18"/><path d="M8 14h8"/>',
            'calendar' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/>',
        ];

        return '<svg class="'.$class.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.($paths[$name] ?? $paths['home']).'</svg>';
    };

    $cards = collect($cards ?? []);
    $reportLinks = collect($reportLinks ?? []);
    $recentAbsensi = collect($recentAbsensi ?? []);
    $recentNilaiAkhir = collect($recentNilaiAkhir ?? []);
@endphp

<section class="overflow-hidden rounded-3xl border border-white/10 bg-white/[0.055] p-5 shadow-2xl shadow-black/25 backdrop-blur-xl sm:p-8">
    <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr] xl:items-center">
        <div class="min-w-0">
            <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Dashboard Kepala Sekolah</p>
            <h2 class="mt-3 text-2xl font-bold text-white sm:text-3xl">Selamat datang, {{ auth()->user()?->name ?? 'Kepala Sekolah' }}</h2>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-300 sm:text-base">
                Gunakan dashboard ini untuk melihat laporan absensi, laporan nilai akademik, serta daftar guru dan siswa sebagai bahan pengambilan keputusan di sekolah.
            </p>
        </div>

        <div class="grid gap-3 sm:grid-cols-2">
            @foreach ($reportLinks->take(2) as $link)
                <a href="{{ $link['href'] }}" class="rounded-2xl border border-white/10 bg-slate-950/30 p-4 transition hover:border-teal-300/30 hover:bg-white/10">
                    <div class="flex items-center gap-3">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-teal-300/15 text-teal-200">
                            {!! $icon($loop->index === 0 ? 'file' : 'chart', 'h-5 w-5') !!}
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-white">{{ $link['label'] }}</p>
                            <p class="mt-1 text-xs text-slate-400">{{ $link['note'] }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ($cards as $card)
        <article class="rounded-3xl border border-white/10 bg-white/[0.055] p-5 shadow-2xl shadow-black/20 backdrop-blur-xl">
            <div class="flex items-start gap-4">
                <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-slate-700/50 text-teal-200">
                    {!! $icon(match ($card['label']) {
                        'Guru' => 'users',
                        'Siswa' => 'school',
                        'Absensi Hari Ini' => 'calendar',
                        default => 'chart',
                    }, 'h-6 w-6') !!}
                </div>
                <div class="min-w-0">
                    <p class="text-3xl font-black leading-none text-white">{{ $card['value'] }}</p>
                    <h3 class="mt-2 text-sm font-semibold uppercase tracking-[0.2em] text-slate-200">{{ $card['label'] }}</h3>
                </div>
            </div>
        </article>
    @endforeach
</section>

<section class="grid gap-6 xl:grid-cols-2">
    <article class="rounded-3xl border border-white/10 bg-slate-900/60 p-5 shadow-2xl sm:p-6">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h3 class="text-2xl font-semibold text-white">Pintasan Laporan</h3>
                <p class="mt-1 text-sm text-slate-400">Akses cepat ke data yang paling sering dipakai.</p>
            </div>
            {!! $icon('file', 'h-5 w-5 text-teal-200') !!}
        </div>
        <div class="mt-5 space-y-3">
            @foreach ($reportLinks as $link)
                <a href="{{ $link['href'] }}" class="block rounded-2xl border border-white/10 bg-white/5 p-4 transition hover:bg-white/10">
                    <p class="text-base font-semibold text-white">{{ $link['label'] }}</p>
                    <p class="mt-1 text-sm text-slate-400">{{ $link['note'] }}</p>
                </a>
            @endforeach
        </div>
    </article>

    <article class="rounded-3xl border border-white/10 bg-slate-900/60 p-5 shadow-2xl sm:p-6">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h3 class="text-2xl font-semibold text-white">Cuplikan Data Terbaru</h3>
                <p class="mt-1 text-sm text-slate-400">Absensi dan nilai terbaru yang masuk ke sistem.</p>
            </div>
            {!! $icon('chart', 'h-5 w-5 text-teal-200') !!}
        </div>

        <div class="mt-5 grid gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Absensi Terbaru</p>
                <div class="mt-2 space-y-2">
                    @forelse ($recentAbsensi as $record)
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                            <p class="text-sm font-semibold text-white">{{ $record->siswa?->nama_siswa ?? '-' }}</p>
                            <p class="mt-1 text-xs text-slate-400">
                                {{ $record->kelas?->nama_kelas ?? '-' }} · {{ $record->jadwal?->mataPelajaran?->nama_mapel ?? '-' }} · {{ ucfirst($record->status_kehadiran ?? '-') }}
                            </p>
                        </div>
                    @empty
                        <p class="rounded-2xl border border-dashed border-white/10 bg-slate-950/20 p-4 text-sm text-slate-400">Belum ada absensi terbaru.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Nilai Terbaru</p>
                <div class="mt-2 space-y-2">
                    @forelse ($recentNilaiAkhir as $record)
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $record->siswa?->nama_siswa ?? '-' }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ $record->kelas?->nama_kelas ?? '-' }} · {{ $record->mataPelajaran?->nama_mapel ?? '-' }}</p>
                                </div>
                                <p class="text-sm font-bold text-teal-200">{{ number_format((float) $record->nilai_akhir, 2) }}</p>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">{{ $record->predikat ?? '-' }}</p>
                        </div>
                    @empty
                        <p class="rounded-2xl border border-dashed border-white/10 bg-slate-950/20 p-4 text-sm text-slate-400">Belum ada nilai akhir terbaru.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </article>
</section>
