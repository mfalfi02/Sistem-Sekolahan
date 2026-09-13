@php
    $cardMeta = [
        ['subtitle' => 'Semua pengguna terdaftar', 'icon' => 'users', 'tone' => 'from-violet-500/80 to-indigo-500/60 text-violet-100 shadow-violet-500/20'],
        ['subtitle' => 'Siswa aktif saat ini', 'icon' => 'student', 'tone' => 'from-amber-400/85 to-yellow-600/60 text-amber-50 shadow-amber-500/20'],
        ['subtitle' => 'Total guru terdaftar', 'icon' => 'teacher', 'tone' => 'from-emerald-400/80 to-green-700/60 text-emerald-50 shadow-emerald-500/20'],
        ['subtitle' => 'Data nilai tersimpan', 'icon' => 'book', 'tone' => 'from-sky-400/80 to-blue-700/60 text-sky-50 shadow-blue-500/20'],
    ];

    $dashboardIcon = function (string $name, string $class = 'h-7 w-7') {
        $paths = [
            'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9"/><path d="M16 3.1a4 4 0 0 1 0 7.8"/>',
            'student' => '<path d="m22 10-10-5-10 5 10 5 10-5Z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/><path d="M22 10v6"/>',
            'teacher' => '<path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/><path d="m15 11 2 2 4-4"/>',
            'book' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z"/>',
            'user' => '<path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/>',
            'chart' => '<path d="M3 3v18h18"/><path d="M7 16V9"/><path d="M12 16V5"/><path d="M17 16v-3"/>',
            'grid' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
            'calendar' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/>',
            'arrow-up' => '<path d="M12 19V5"/><path d="m5 12 7-7 7 7"/><path d="M5 21h14"/>',
            'file' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h5"/>',
        ];

        return '<svg class="'.$class.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.($paths[$name] ?? $paths['chart']).'</svg>';
    };

    $featureButtons = [];
    $recentActivities = collect($recentActivities ?? []);
@endphp

<div class="space-y-7">
    <section class="overflow-hidden rounded-3xl border border-white/10 bg-white/[0.055] p-6 shadow-2xl shadow-black/25 backdrop-blur-2xl sm:p-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-5">
                <div class="grid h-20 w-20 shrink-0 place-items-center rounded-full bg-slate-700/60 text-slate-300 ring-1 ring-white/10 sm:h-24 sm:w-24">
                    {!! $dashboardIcon('user', 'h-12 w-12 sm:h-14 sm:w-14') !!}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white sm:text-3xl">Dashboard Admin</h2>
                    <p class="mt-3 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                        Kelola seluruh sistem sekolah dan lihat statistik lengkap.
                    </p>
                </div>
            </div>

        </div>
    </section>

    @if (! empty($tahunAjaranList) && $tahunAjaranList->isNotEmpty())
        <section class="overflow-hidden rounded-3xl border border-white/10 bg-white/[0.055] p-5 shadow-2xl shadow-black/20 backdrop-blur-xl sm:p-6">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs uppercase tracking-[0.35em] text-teal-200/70">Pengaturan Tahun Ajaran</p>
                    <h3 class="mt-3 text-2xl font-bold text-white">Aktifkan tahun ajaran dari dashboard</h3>
                    <p class="mt-3 text-sm leading-6 text-slate-300">
                        Tahun ajaran yang aktif akan dipakai untuk jadwal, absensi, penilaian, dan tampilan data yang sedang berjalan.
                        Data lama tetap tersimpan dan bisa dilihat kembali kapan saja.
                    </p>
                    @if ($activeTahunAjaran)
                        <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-400/10 px-4 py-2 text-sm font-semibold text-emerald-100">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                            Aktif saat ini: {{ $activeTahunAjaran->nama_tahun_ajaran }} {{ $activeTahunAjaran->semester }}
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('masters.activate', ['tahun-ajaran', optional($activeTahunAjaran)->id ?? $tahunAjaranList->first()->id]) }}" class="min-w-0 rounded-3xl border border-white/10 bg-slate-950/25 p-4 shadow-lg shadow-black/10 lg:w-[28rem]">
                    @csrf
                    <label class="block text-sm font-semibold text-slate-200">Pilih Tahun Ajaran</label>
                    <select
                        name="tahun_ajaran_id"
                        class="mt-3 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-teal-300/50"
                    >
                        @foreach ($tahunAjaranList as $tahun)
                            <option value="{{ $tahun->id }}" @selected((int) ($activeTahunAjaran->id ?? 0) === $tahun->id)>
                                {{ $tahun->nama_tahun_ajaran }} {{ $tahun->semester }}{{ $tahun->status_aktif ? ' - Aktif' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-3 text-xs leading-5 text-slate-400">
                        Menekan tombol aktifkan akan mematikan tahun ajaran lama dan menjadikan pilihan ini sebagai tahun aktif.
                    </p>
                    <button
                        type="submit"
                        class="mt-4 inline-flex w-full items-center justify-center rounded-2xl bg-teal-300 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-teal-200"
                    >
                        Jadikan Aktif
                    </button>
                </form>
            </div>
        </section>
    @endif

    <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($cards as $card)
            @php($meta = $cardMeta[$loop->index] ?? $cardMeta[0])
            <article class="group rounded-3xl border border-white/10 bg-white/[0.055] p-6 shadow-2xl shadow-black/20 backdrop-blur-xl transition duration-200 hover:-translate-y-1 hover:border-indigo-300/20 hover:bg-white/[0.075]">
                <div class="flex items-center gap-5">
                    <div class="grid h-16 w-16 shrink-0 place-items-center rounded-full bg-gradient-to-br {{ $meta['tone'] }} shadow-xl">
                        {!! $dashboardIcon($meta['icon'], 'h-8 w-8') !!}
                    </div>
                    <div>
                        <p class="text-3xl font-black leading-none text-white">{{ $card['value'] }}</p>
                        <h3 class="mt-3 text-lg font-semibold text-slate-200">{{ $card['label'] }}</h3>
                        <p class="mt-3 text-sm text-slate-400">{{ $meta['subtitle'] }}</p>
                    </div>
                </div>
            </article>
        @endforeach
    </section>

    <section class="grid gap-5 lg:grid-cols-1 xl:grid-cols-2">
        <div id="aktivitas-kelas" class="rounded-3xl border border-white/10 bg-white/[0.055] p-5 shadow-2xl shadow-black/25 backdrop-blur-2xl sm:p-6">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-white">Aktivitas Terbaru</h3>
                </div>
            </div>

            <div data-table-filter class="overflow-hidden rounded-2xl border border-white/10 bg-slate-950/[0.15]">
                <div class="border-b border-white/10 px-4 py-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-white">Cari Aktivitas</p>
                            <p class="text-xs text-slate-400">Filter berdasarkan waktu, aktivitas, atau keterangan.</p>
                        </div>
                        <input type="search" data-table-filter-input placeholder="Cari aktivitas..." class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm text-white placeholder-slate-500 outline-none focus:border-indigo-300/50 sm:max-w-sm">
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="border-b border-white/10 text-xs font-bold uppercase tracking-wider text-slate-400">
                            <tr>
                                <th class="px-4 py-4">Waktu</th>
                                <th class="px-4 py-4">Aktivitas</th>
                                <th class="px-4 py-4">Oleh</th>
                                <th class="px-4 py-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10 text-sm text-slate-200">
                            @forelse ($recentActivities as $activity)
                                <tr data-table-filter-row class="transition hover:bg-indigo-500/10">
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-300">{{ $activity['waktu'] ?? '-' }}</td>
                                    <td class="px-4 py-4 font-medium">{{ $activity['aktivitas'] ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-4 py-4">{{ $activity['oleh'] ?? '-' }}</td>
                                    <td class="px-4 py-4 text-slate-300">{{ $activity['keterangan'] ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-400">Belum ada aktivitas yang tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p data-table-filter-empty hidden class="px-4 py-4 text-center text-sm text-slate-400">Tidak ada aktivitas yang cocok dengan pencarian.</p>
            </div>

            <a href="{{ route('activities.index') }}" class="mt-6 inline-flex rounded-xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-100 transition hover:-translate-y-0.5 hover:bg-white/10">
                Lihat Semua Aktivitas
            </a>
        </div>

        <div class="rounded-3xl border border-white/10 bg-white/[0.055] p-5 shadow-2xl shadow-black/25 backdrop-blur-2xl sm:p-6">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-white">Aktivitas Per Kelas Hari Ini</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Persentase hadir, guru yang masuk, dan mata pelajaran.</p>
                </div>
                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <label class="sr-only" for="kelas_id">Pilih Kelas</label>
                    <select id="kelas_id" name="kelas_id" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-teal-300/50">
                        <option value="" @selected(empty($selectedKelasId))>Semua Kelas</option>
                        @foreach ($kelasFilterList ?? [] as $kelas)
                            <option value="{{ $kelas->id }}" @selected((int) ($selectedKelasId ?? 0) === $kelas->id)>{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-2xl bg-indigo-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-400">Filter</button>
                </form>
            </div>

            <div class="mb-6 flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="rounded-xl border px-5 py-3 text-sm font-bold transition {{ empty($selectedKelasId) ? 'border-indigo-300/20 bg-indigo-500/25 text-indigo-100 shadow-lg shadow-indigo-500/10' : 'border-white/10 bg-slate-950/20 text-slate-200 hover:bg-white/10' }}">
                    Semua Kelas
                </a>
                @foreach ($kelasFilterList ?? [] as $kelas)
                    <a
                        href="{{ route('dashboard', ['kelas_id' => $kelas->id]) }}"
                        class="rounded-xl border px-5 py-3 text-sm font-bold transition {{ (int) ($selectedKelasId ?? 0) === $kelas->id ? 'border-indigo-300/20 bg-indigo-500/25 text-indigo-100 shadow-lg shadow-indigo-500/10' : 'border-white/10 bg-slate-950/20 text-slate-200 hover:bg-white/10' }}"
                    >
                        {{ $kelas->nama_kelas }}
                    </a>
                @endforeach
            </div>

            <div class="space-y-4">
                @forelse ($classActivity ?? [] as $activity)
                    <article class="rounded-lg md:rounded-2xl border border-white/10 bg-slate-950/[0.18] p-3 md:p-4">
                        <div class="grid gap-3 md:gap-4 grid-cols-1 md:grid-cols-[0.6fr_1.4fr] md:items-start">
                            <div class="min-w-0">
                                <p class="truncate text-base md:text-lg font-bold text-white">{{ $activity['kelas']->nama_kelas }}</p>
                                <p class="mt-2 text-base md:text-lg font-bold text-white">{{ $activity['persentase_hadir'] }}%</p>
                                <p class="mt-1 text-xs md:text-sm text-slate-300">
                                    {{ $activity['hadir'] }}/{{ $activity['total_siswa'] }} hadir
                                </p>
                            </div>

                            <div class="grid gap-2 grid-cols-3">
                                <div class="rounded-lg border border-white/10 bg-white/[0.035] p-2 md:p-3">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Guru</p>
                                    <p class="mt-1 font-bold text-white truncate text-xs md:text-sm">{{ $activity['guru'] }}</p>
                                </div>
                                <div class="rounded-lg border border-white/10 bg-white/[0.035] p-2 md:p-3">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Mapel</p>
                                    <p class="mt-1 font-bold text-white truncate text-xs md:text-sm">{{ $activity['mata_pelajaran'] }}</p>
                                </div>
                                <div class="rounded-lg border border-white/10 bg-white/[0.035] p-2 md:p-3">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Jam</p>
                                    <p class="mt-1 font-bold text-white truncate text-xs md:text-sm">{{ $activity['jam'] }}</p>
                                </div>
                            </div>
                        </div>

                        @php($jadwalLainnya = collect($activity['jadwal_list'] ?? [])->slice(1))
                        @if ($jadwalLainnya->isNotEmpty())
                            <div class="mt-3 space-y-3">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Jadwal lainnya hari ini</p>
                                <div class="space-y-3">
                                    @foreach ($jadwalLainnya as $jadwal)
                                        <article class="rounded-2xl border border-white/10 bg-slate-950/[0.16] p-3 md:p-4">
                                            <div class="grid gap-3 md:grid-cols-[0.55fr_1.45fr] md:items-start">
                                                <div class="min-w-0">
                                                    <p class="truncate text-sm md:text-base font-bold text-white">{{ $activity['kelas']->nama_kelas }}</p>
                                                    <p class="mt-2 text-2xl md:text-3xl font-black leading-none text-white">{{ $jadwal['persentase_hadir'] }}%</p>
                                                    <p class="mt-1 text-xs md:text-sm text-slate-300">{{ $jadwal['hadir'] }}/{{ $jadwal['total_siswa'] }} hadir</p>
                                                </div>

                                                <div class="grid gap-2 grid-cols-1 sm:grid-cols-3">
                                                    <div class="rounded-xl border border-white/10 bg-white/[0.035] p-2 md:p-3">
                                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Guru</p>
                                                        <p class="mt-1 truncate text-xs md:text-sm font-bold text-white">{{ $jadwal['guru'] }}</p>
                                                    </div>
                                                    <div class="rounded-xl border border-white/10 bg-white/[0.035] p-2 md:p-3">
                                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Mapel</p>
                                                        <p class="mt-1 truncate text-xs md:text-sm font-bold text-white">{{ $jadwal['mata_pelajaran'] }}</p>
                                                    </div>
                                                    <div class="rounded-xl border border-white/10 bg-white/[0.035] p-2 md:p-3">
                                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Jam</p>
                                                        <p class="mt-1 text-xs md:text-sm font-bold text-white">{{ $jadwal['jam'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-700/50 shadow-inner shadow-black/30">
                            <div
                                class="h-full rounded-full bg-gradient-to-r from-indigo-400 via-sky-400 to-emerald-300 shadow-lg shadow-sky-500/30"
                                style="width: {{ min(100, max(0, $activity['persentase_hadir'])) }}%;"
                            ></div>
                        </div>
                        <p class="mt-2 text-xs text-slate-400">
                            {{ $activity['jumlah_jadwal'] }} jadwal hari ini
                        </p>
                    </article>
                @empty
                    <div class="rounded-lg md:rounded-2xl border border-white/10 bg-slate-950/20 p-6 md:p-8 text-center text-xs md:text-sm text-slate-400">
                        Belum ada jadwal aktif hari ini.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
