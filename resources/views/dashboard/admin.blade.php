<section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
    <h2 class="text-3xl font-semibold">Dashboard Admin</h2>
    <p class="text-slate-300 mt-2">Kelola seluruh sistem sekolah dan lihat statistik lengkap.</p>
    <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15 mt-4 inline-block">
        Lihat Dashboard Umum
    </a>
</section>

<div class="grid gap-8 lg:grid-cols-2">
    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
        <h3 class="text-2xl font-semibold mb-6">Statistik Sistem</h3>
        <div class="grid gap-4 md:grid-cols-2">
            @foreach ($cards as $card)
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-center">
                    <div class="text-3xl mb-2">{{ $card['icon'] }}</div>
                    <p class="text-2xl font-bold">{{ $card['value'] }}</p>
                    <p class="text-sm text-slate-400 mt-1">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
        <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h3 class="text-2xl font-semibold">Aktivitas Per Kelas Hari Ini</h3>
                <p class="text-sm text-slate-400">Persentase hadir, guru yang masuk, dan mata pelajaran.</p>
            </div>
            <div class="text-sm text-teal-300">
                <p>Hari ini</p>
                <p class="text-right text-xs text-slate-400">{{ $selectedKelasLabel ?? 'Semua Kelas' }}</p>
            </div>
        </div>
        <div class="mb-6 flex flex-wrap gap-2">
            <a href="{{ route('dashboard') }}" class="rounded-full border border-white/10 px-4 py-2 text-sm {{ empty($selectedKelasId) ? 'bg-teal-300 text-slate-950' : 'bg-white/5 text-slate-200 hover:bg-white/10' }}">
                Semua Kelas
            </a>
            @foreach ($kelasFilterList ?? [] as $kelas)
                <a
                    href="{{ route('dashboard', ['kelas_id' => $kelas->id]) }}"
                    class="rounded-full border border-white/10 px-4 py-2 text-sm {{ (int) ($selectedKelasId ?? 0) === $kelas->id ? 'bg-teal-300 text-slate-950' : 'bg-white/5 text-slate-200 hover:bg-white/10' }}"
                >
                    {{ $kelas->nama_kelas }}
                </a>
            @endforeach
        </div>
        <div class="space-y-4">
            @forelse ($classActivity ?? [] as $activity)
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-slate-400">{{ $activity['kelas']->nama_kelas }}</p>
                            <h4 class="mt-1 text-xl font-semibold">
                                {{ $activity['persentase_hadir'] }}% hadir
                            </h4>
                            <p class="mt-1 text-sm text-slate-300">
                                {{ $activity['hadir'] }} dari {{ $activity['total_siswa'] }} siswa hadir
                            </p>
                        </div>
                        <div class="grid gap-3 md:grid-cols-3 lg:min-w-[50%]">
                            <div class="rounded-xl bg-slate-950/40 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Guru</p>
                                <p class="mt-1 font-medium text-slate-100">{{ $activity['guru'] }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-950/40 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Mapel</p>
                                <p class="mt-1 font-medium text-slate-100">{{ $activity['mata_pelajaran'] }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-950/40 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Jam</p>
                                <p class="mt-1 font-medium text-slate-100">{{ $activity['jam'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 h-2 rounded-full bg-white/10">
                        <div
                            class="h-2 rounded-full bg-gradient-to-r from-teal-300 to-emerald-400"
                            style="width: {{ min(100, max(0, $activity['persentase_hadir'])) }}%;"
                        ></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">
                        {{ $activity['jumlah_jadwal'] }} jadwal aktif terdaftar untuk kelas ini hari ini.
                    </p>
                </div>
            @empty
                <p class="text-center text-slate-400 py-8">Belum ada jadwal aktif hari ini.</p>
            @endforelse
        </div>
    </section>
</div>

<section class="grid gap-6 lg:grid-cols-3">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
        <h3 class="text-xl font-semibold">Quick Actions</h3>
        <div class="mt-4 space-y-2">
            <a href="/masters/siswa" class="block p-3 rounded-xl bg-white/5 hover:bg-white/10">Kelola Siswa</a>
            <a href="/masters/jadwal" class="block p-3 rounded-xl bg-white/5 hover:bg-white/10">Kelola Jadwal</a>
            <a href="{{ route('kenaikan-kelas.index') }}" class="block p-3 rounded-xl bg-white/5 hover:bg-white/10">Kenaikan Kelas</a>
            <a href="/rekap/nilai" class="block p-3 rounded-xl bg-white/5 hover:bg-white/10">Rekap Nilai</a>
            <a href="/rekap/absensi" class="block p-3 rounded-xl bg-white/5 hover:bg-white/10">Rekap Absensi</a>
        </div>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
        <h3 class="text-xl font-semibold">Reports</h3>
        <p class="text-slate-300 mt-3">Generate laporan lengkap untuk semua data.</p>
    </div>
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
        <h3 class="text-xl font-semibold">System Status</h3>
        <p class="text-emerald-400 mt-3">Semua layanan aktif</p>
    </div>
</section>
