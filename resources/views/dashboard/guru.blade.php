@php
    $themeIcon = function (string $name, string $class = 'h-5 w-5') {
        $paths = [
            'book' => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H7a3 3 0 0 0-3 3V5.5Z"/><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>',
            'calendar' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/>',
            'chart' => '<path d="M4 19V5"/><path d="M4 19h17"/><path d="M8 16v-5"/><path d="M13 16V8"/><path d="M18 16v-9"/>',
            'check' => '<path d="M20 6 9 17l-5-5"/>',
            'clock' => '<path d="M12 6v6l4 2"/><path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
            'file' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h5"/>',
            'target' => '<path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"/><path d="M12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z"/><path d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"/>',
            'user' => '<path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/>',
        ];

        return '<svg class="'.$class.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.($paths[$name] ?? $paths['book']).'</svg>';
    };

    $cardIcons = [
        'Absensi Hari Ini' => 'calendar',
        'Nilai Masuk Hari Ini' => 'file',
        'Kelas Diajar' => 'book',
        'Mapel' => 'chart',
    ];

    $cards = collect($cards ?? []);
    $jadwalList = collect($jadwalHariIni ?? []);
    $recentNilaiList = collect($recentNilai ?? []);
    $guruName = auth()->user()?->name ?? 'Guru';
@endphp

<section class="overflow-hidden rounded-3xl border border-white/10 bg-white/[0.055] p-5 shadow-2xl shadow-black/25 backdrop-blur-xl sm:p-8">
    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr] xl:items-center">
        <div class="min-w-0">
            <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Dashboard Guru</p>
            <h2 class="mt-3 text-2xl font-bold text-white sm:text-3xl">Selamat datang, {{ $guruName }}</h2>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-300 sm:text-base">
                Pantau jadwal, input absensi, dan catat nilai dengan alur yang cepat, rapi, dan nyaman dipakai setiap hari.
            </p>
        </div>

        <div class="grid gap-3 sm:grid-cols-2">
            <div class="rounded-2xl border border-white/10 bg-slate-950/30 p-4">
                <div class="flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-teal-300/15 text-teal-200">
                        {!! $themeIcon('calendar', 'h-5 w-5') !!}
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Hari ini</p>
                        <p class="mt-1 text-lg font-semibold text-white">{{ $hariIni ?? now()->translatedFormat('l') }}</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-white/10 bg-slate-950/30 p-4">
                <div class="flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-indigo-300/15 text-indigo-200">
                        {!! $themeIcon('book', 'h-5 w-5') !!}
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Jadwal</p>
                        <p class="mt-1 text-lg font-semibold text-white">{{ $jadwalList->count() }} pelajaran</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @forelse ($cards as $card)
        @php
            $tone = match ($loop->index) {
                0 => 'from-sky-400/80 to-blue-700/60 text-sky-50 shadow-sky-500/20',
                1 => 'from-emerald-400/80 to-green-700/60 text-emerald-50 shadow-emerald-500/20',
                2 => 'from-amber-400/85 to-yellow-600/60 text-amber-50 shadow-amber-500/20',
                default => 'from-violet-500/80 to-indigo-500/60 text-violet-100 shadow-violet-500/20',
            };
        @endphp
        <article class="rounded-3xl border border-white/10 bg-white/[0.055] p-5 shadow-2xl shadow-black/20 backdrop-blur-xl transition hover:-translate-y-1 hover:border-indigo-300/20 hover:bg-white/[0.075]">
            <div class="flex items-start gap-4">
                <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-gradient-to-br {{ $tone }}">
                    {!! $themeIcon($cardIcons[$card['label']] ?? 'target', 'h-6 w-6') !!}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-3xl font-black leading-none text-white">{{ $card['value'] }}</p>
                    <h3 class="mt-2 text-sm font-semibold uppercase tracking-[0.2em] text-slate-200">{{ $card['label'] }}</h3>
                    <p class="mt-2 text-sm text-slate-400">
                        {{ $card['label'] === 'Absensi Hari Ini' ? 'Jumlah presensi masuk hari ini' : ($card['label'] === 'Nilai Masuk Hari Ini' ? 'Nilai yang baru dicatat hari ini' : ($card['label'] === 'Kelas Diajar' ? 'Total kelas yang sedang diajar' : 'Jumlah mata pelajaran yang terdata')) }}
                    </p>
                </div>
            </div>
        </article>
    @empty
        <div class="sm:col-span-2 xl:col-span-4 rounded-3xl border border-white/10 bg-slate-900/60 p-6 text-center text-sm text-slate-400">
            Belum ada ringkasan harian.
        </div>
    @endforelse
</section>

<section class="grid gap-6 xl:grid-cols-2">
    <article class="h-full rounded-3xl border border-white/10 bg-slate-900/60 p-5 shadow-2xl sm:p-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-semibold text-white">Jadwal Hari Ini</h3>
                <p class="mt-1 text-sm text-slate-400">{{ $hariIni ?? 'Hari ini' }}</p>
            </div>
            <span class="rounded-full bg-teal-300/15 px-3 py-1 text-xs font-semibold text-teal-200">
                {{ $jadwalList->count() }} pelajaran
            </span>
        </div>

        <div class="mt-5 space-y-3">
            @forelse ($jadwalList as $jadwal)
                <div class="grid gap-4 rounded-2xl border border-white/10 bg-white/5 p-4 sm:grid-cols-[96px_1fr_auto] sm:items-center">
                    <div class="flex items-center gap-3 rounded-2xl bg-slate-950/30 px-3 py-2">
                        {!! $themeIcon('clock', 'h-4 w-4 text-teal-200') !!}
                        <div>
                            <p class="text-[11px] uppercase tracking-[0.2em] text-slate-400">Jam</p>
                            <p class="text-sm font-semibold text-white">{{ substr((string) $jadwal->jam_mulai, 0, 5) }}</p>
                        </div>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ $jadwal->kelas?->nama_kelas ?? '-' }}</p>
                        <p class="mt-1 truncate text-base font-semibold text-slate-100">{{ $jadwal->mataPelajaran?->nama_mapel ?? '-' }}</p>
                        <p class="mt-1 text-sm text-slate-400">{{ substr((string) $jadwal->jam_mulai, 0, 5) }} - {{ substr((string) $jadwal->jam_selesai, 0, 5) }}</p>
                    </div>

                    <div class="rounded-xl border border-white/10 bg-slate-950/25 px-3 py-2 text-center">
                        <p class="text-[11px] uppercase tracking-[0.2em] text-slate-400">Ruang</p>
                        <p class="mt-1 text-sm font-semibold text-white">{{ $jadwal->ruang ?? '-' }}</p>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-white/10 bg-slate-950/20 p-8 text-center">
                    <p class="text-sm text-slate-400">Belum ada jadwal aktif hari ini.</p>
                </div>
            @endforelse
        </div>
    </article>

    <article class="h-full rounded-3xl border border-white/10 bg-slate-900/60 p-5 shadow-2xl sm:p-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-semibold text-white">Nilai Terakhir Dimasukkan</h3>
                <p class="mt-1 text-sm text-slate-400">Data terbaru yang baru saja dicatat.</p>
            </div>
            <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-slate-300">
                {{ $recentNilaiList->count() }} data
            </span>
        </div>

        <div class="mt-5 space-y-3">
            @forelse ($recentNilaiList as $nilai)
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="truncate text-base font-semibold text-slate-100">{{ $nilai->siswa?->nama_siswa ?? '-' }}</p>
                            <p class="mt-1 text-sm text-slate-400">{{ $nilai->tanggal_nilai?->format('d M Y') ?? '-' }}</p>
                        </div>
                        <div class="shrink-0 rounded-2xl bg-teal-300/15 px-3 py-2 text-right">
                            <p class="text-[11px] uppercase tracking-[0.2em] text-teal-200">Nilai</p>
                            <p class="text-lg font-black text-teal-100">{{ number_format((float) $nilai->nilai, 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-slate-300">
                            {{ $nilai->kelas?->nama_kelas ?? '-' }}
                        </span>
                        <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-slate-300">
                            {{ $nilai->jenisPenilaian?->nama_jenis ?? 'Nilai' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-white/10 bg-slate-950/20 p-8 text-center">
                    <p class="text-sm text-slate-400">Belum ada nilai yang dimasukkan.</p>
                </div>
            @endforelse
        </div>
    </article>
</section>
