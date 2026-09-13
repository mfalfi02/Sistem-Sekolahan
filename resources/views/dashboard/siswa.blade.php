@php
    $gradeAverage = (float) ($avgGrade ?? 0);
    $gradePercent = min(100, max(0, $gradeAverage));
    $attendanceValue = $cards[0]['value'] ?? '0%';
    $nilaiTersedia = $cards[1]['value'] ?? '0';
    $jadwalList = collect($jadwalHariIni ?? []);
    $jadwalCount = $jadwalList->count();
    $targetReached = $gradeAverage >= 75;
    $statusBadgeClass = $targetReached
        ? 'bg-emerald-300/15 text-emerald-200'
        : 'bg-amber-300/15 text-amber-200';

    $gradeChartList = collect($gradeChartData ?? []);
    $chartCount = max(1, $gradeChartList->count());
    $chartPoints = $gradeChartList->values()->map(function ($item, $index) use ($chartCount) {
        $x = $chartCount > 1 ? 40 + (($index * 648) / ($chartCount - 1)) : 364;
        $y = 145 - ((((float) $item['score']) / 100) * 90);

        return [
            'x' => round($x, 2),
            'y' => round($y, 2),
            'score' => $item['score'],
            'label' => $item['label'],
        ];
    });
    $polylinePoints = $chartPoints->map(fn ($point) => $point['x'].','.$point['y'])->implode(' ');
    $polygonPoints = $chartPoints->isNotEmpty()
        ? $polylinePoints.' '.$chartPoints->last()['x'].',145 '.$chartPoints->first()['x'].',145'
        : '';
    $calendarStart = now('Asia/Jakarta')->copy()->startOfMonth()->startOfWeek();
    $calendarEnd = now('Asia/Jakarta')->copy()->endOfMonth()->endOfWeek();
    $calendarWeeks = collect();

    for ($date = $calendarStart->copy(); $date->lte($calendarEnd); $date->addDay()) {
        if ($calendarWeeks->isEmpty() || $date->isMonday()) {
            $calendarWeeks->push(collect());
        }

        $calendarWeeks[$calendarWeeks->count() - 1]->push([
            'date' => $date->copy(),
            'is_current_month' => $date->month === now('Asia/Jakarta')->month,
            'is_today' => $date->isToday(),
        ]);
    }

    $icon = function ($name, $class = 'h-5 w-5') {
        $paths = [
            'book' => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H7a3 3 0 0 0-3 3V5.5Z" /><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />',
            'calendar' => '<path d="M8 2v4" /><path d="M16 2v4" /><path d="M3 10h18" /><path d="M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z" />',
            'chart' => '<path d="M4 19V5" /><path d="M4 19h17" /><path d="M8 16v-5" /><path d="M13 16V8" /><path d="M18 16v-9" />',
            'check' => '<path d="M20 6 9 17l-5-5" />',
            'clock' => '<path d="M12 6v6l4 2" /><path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
            'file' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z" /><path d="M14 2v6h6" /><path d="M8 13h8" /><path d="M8 17h5" />',
            'megaphone' => '<path d="m3 11 18-5v12L3 13v-2Z" /><path d="M11 14a4 4 0 0 0 5 4" />',
            'target' => '<path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" /><path d="M12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" /><path d="M12 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />',
            'user' => '<path d="M20 21a8 8 0 0 0-16 0" /><path d="M12 13a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" />',
        ];

        return '<svg class="'.$class.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.($paths[$name] ?? $paths['chart']).'</svg>';
    };

    $summaryCards = [
        ['label' => 'Rata-rata', 'value' => number_format($gradeAverage, 2), 'note' => $targetReached ? 'Target aman' : 'Butuh perhatian', 'icon' => 'book'],
        ['label' => 'Kehadiran', 'value' => $attendanceValue, 'note' => 'Bulan ini', 'icon' => 'check'],
        ['label' => 'Nilai', 'value' => $nilaiTersedia, 'note' => 'Data masuk', 'icon' => 'file'],
        ['label' => 'Jadwal', 'value' => (string) $jadwalCount, 'note' => $hariIni ?? 'Hari ini', 'icon' => 'calendar'],
    ];

@endphp

<section class="space-y-4">
    <section class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-2xl backdrop-blur-xl md:p-5">
        <div class="grid gap-4 xl:grid-cols-[1fr_auto] xl:items-center">
            <div class="min-w-0">
                <p class="text-xs uppercase tracking-[0.24em] text-teal-200/70">Portal Siswa</p>
                <div class="mt-2 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                    <div class="min-w-0">
                        <h2 class="truncate text-2xl font-semibold leading-tight text-slate-100 md:text-3xl">
                            Selamat belajar, {{ $user?->name ?? 'Siswa' }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-400">
                            Ringkasan belajar ditampilkan singkat. Detail nilai dan presensi ada di fitur masing-masing.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 xl:w-[520px]">
                @foreach ($summaryCards as $card)
                    <article class="rounded-xl border border-white/10 bg-slate-950/30 p-3">
                        <div class="flex items-center gap-2 text-slate-400">
                            {!! $icon($card['icon'], 'h-4 w-4 text-teal-200') !!}
                            <p class="truncate text-xs">{{ $card['label'] }}</p>
                        </div>
                        <p class="mt-2 truncate text-xl font-semibold text-slate-100">{{ $card['value'] }}</p>
                        <p class="mt-0.5 truncate text-[11px] text-slate-500">{{ $card['note'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="grid gap-4 xl:grid-cols-[1.25fr_.75fr]">
        <article id="grafik-nilai" class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-2xl">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-100">Grafik Rata-rata Nilai</h3>
                    <p class="text-xs text-slate-400">Nilai rata-rata keseluruhan per semester.</p>
                </div>
                <a href="{{ route('siswa.nilai.index') }}" class="h-9 rounded-xl bg-teal-300 px-3 py-2 text-xs font-bold text-slate-950 hover:bg-teal-200">
                    Lihat Detail
                </a>
            </div>
            <div class="mt-3 h-36">
                @if ($chartPoints->isNotEmpty())
                    <svg viewBox="0 0 720 180" class="h-full w-full overflow-visible">
                        @foreach ([30, 65, 100, 135] as $lineY)
                            <line x1="32" y1="{{ $lineY }}" x2="700" y2="{{ $lineY }}" stroke="rgba(255,255,255,.12)" stroke-width="1" />
                        @endforeach
                        <polyline points="{{ $polylinePoints }}" fill="none" stroke="#5eead4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        <polygon points="{{ $polygonPoints }}" fill="#5eead4" opacity=".10" />
                        @foreach ($chartPoints as $point)
                            <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="4" fill="#5eead4" />
                            <text x="{{ $point['x'] }}" y="{{ $point['y'] - 9 }}" text-anchor="middle" class="fill-slate-200 text-[10px] font-semibold">{{ $point['score'] }}</text>
                            <text x="{{ $point['x'] }}" y="168" text-anchor="middle" class="fill-slate-400 text-[10px]">{{ $point['label'] }}</text>
                        @endforeach
                    </svg>
                @else
                    <div class="flex h-full items-center justify-center rounded-xl border border-white/10 bg-slate-950/30 text-center text-sm text-slate-400">
                        Belum ada nilai akhir untuk dibuat grafik.
                    </div>
                @endif
            </div>
        </article>

        <article id="target-belajar" class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-2xl">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-slate-100">Target Belajar</h3>
                    <p class="text-xs text-slate-400">KKM 75</p>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusBadgeClass }}">
                    {{ $targetReached ? 'Aman' : 'Pantau' }}
                </span>
            </div>
            <div class="mt-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-400">Rata-rata</span>
                    <span class="font-semibold text-teal-200">{{ number_format($gradeAverage, 2) }}/100</span>
                </div>
                <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-slate-950/50">
                    <div class="h-full rounded-full bg-teal-300" style="width: {{ $gradePercent }}%"></div>
                </div>
                <p class="mt-3 rounded-xl border border-white/10 bg-slate-950/30 p-3 text-sm text-slate-300">
                    {{ $targetReached ? 'Pertahankan ritme belajar dan presensi.' : 'Cek fitur detail nilai untuk menentukan mapel prioritas.' }}
                </p>
            </div>
        </article>
    </section>

    <section class="grid gap-4 xl:grid-cols-[1.05fr_.95fr]">
        <article id="jadwal-hari-ini" class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-2xl">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-slate-100">Jadwal Hari Ini</h3>
                    <p class="text-xs text-slate-400">{{ $hariIni ?? 'Hari ini' }}</p>
                </div>
                <span class="rounded-full bg-teal-300/15 px-3 py-1 text-xs font-semibold text-teal-200">{{ $jadwalCount }} pelajaran</span>
            </div>
            <div class="mt-3 space-y-2">
                @forelse ($jadwalList->take(4) as $jadwal)
                    <div class="grid grid-cols-[36px_1fr_auto] items-center gap-2 rounded-xl border border-white/10 bg-slate-950/30 p-2.5">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-teal-300/15 text-xs font-bold text-teal-200">{{ $loop->iteration }}</span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-100">{{ $jadwal->mataPelajaran?->nama_mapel ?? '-' }}</p>
                            <p class="text-xs text-slate-400">{{ \App\Support\IndonesianDateTime::timeRange($jadwal->jam_mulai, $jadwal->jam_selesai) }}</p>
                        </div>
                        <span class="rounded-lg bg-white/10 px-2 py-1 text-xs font-semibold text-slate-300">{{ $jadwal->kelas?->nama_kelas ?? '-' }}</span>
                    </div>
                @empty
                    <p class="rounded-xl border border-white/10 bg-slate-950/30 p-4 text-center text-sm text-slate-400">Belum ada jadwal aktif hari ini.</p>
                @endforelse
            </div>
        </article>

        <div class="grid gap-4 md:grid-cols-[.72fr_1fr]">
            <article id="kalender-akademik" class="rounded-2xl border border-white/10 bg-white/5 p-3 shadow-2xl">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="text-base font-semibold text-slate-100">Kalender</h3>
                    <p class="text-[11px] text-slate-400">{{ \App\Support\IndonesianDateTime::monthYear(now('Asia/Jakarta')) }}</p>
                </div>
                <div class="mt-2 grid grid-cols-7 gap-px text-center text-[9px] text-slate-500">
                    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                        <span>{{ $day }}</span>
                    @endforeach
                </div>
                <div class="mt-1.5 space-y-px">
                    @foreach ($calendarWeeks as $week)
                        <div class="grid grid-cols-7 gap-px">
                            @foreach ($week as $day)
                                <div class="flex aspect-square items-center justify-center rounded-md text-[10px] {{ $day['is_today'] ? 'bg-teal-300 font-bold text-slate-950' : ($day['is_current_month'] ? 'text-slate-200' : 'text-slate-600') }}">
                                    {{ $day['date']->day }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </article>

            <article id="pengumuman" class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-2xl">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-lg font-semibold text-slate-100">Pengumuman</h3>
                    {!! $icon('megaphone', 'h-5 w-5 text-teal-200') !!}
                </div>
                <div class="mt-3 space-y-2">
                    @foreach (['Cek jadwal sebelum mulai belajar.', 'Pantau nilai dan presensi berkala.', 'Laporkan data yang tidak sesuai.'] as $announcement)
                        <div class="flex gap-2 rounded-xl border border-white/10 bg-slate-950/30 p-2.5">
                            <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-teal-300"></span>
                            <p class="text-sm leading-snug text-slate-300">{{ $announcement }}</p>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </section>

</section>
