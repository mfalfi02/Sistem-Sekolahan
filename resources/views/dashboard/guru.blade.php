<section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
    <h2 class="text-3xl font-semibold">Dashboard Guru</h2>
    <p class="text-slate-300 mt-2">Pantau jadwal, absensi, dan input nilai siswa Anda.</p>
    <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15 mt-4 inline-block">
        Lihat Dashboard Umum
    </a>
</section>

<div class="grid gap-8 lg:grid-cols-2">
    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
        <h3 class="text-2xl font-semibold mb-6">Statistik Harian</h3>
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
        <h3 class="text-2xl font-semibold mb-6">Jadwal Hari Ini</h3>
        <div class="space-y-3">
            @forelse ($jadwalHariIni ?? [] as $jadwal)
                <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                    <p class="text-sm uppercase tracking-[0.2em] text-slate-400">{{ $jadwal->kelas?->nama_kelas }}</p>
                    <p class="mt-1 font-semibold text-slate-100">{{ $jadwal->mataPelajaran?->nama_mapel }}</p>
                    <p class="text-sm text-slate-300">{{ substr((string) $jadwal->jam_mulai, 0, 5) }} - {{ substr((string) $jadwal->jam_selesai, 0, 5) }} · Ruang {{ $jadwal->ruang ?? '-' }}</p>
                </div>
            @empty
                <p class="text-center text-slate-400 py-8">Belum ada jadwal aktif hari ini.</p>
            @endforelse
        </div>
    </section>
</div>

<section class="grid gap-8 lg:grid-cols-2">
    <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
        <h3 class="text-2xl font-semibold mb-6">Nilai Terakhir Dimasukkan</h3>
        <div class="space-y-3">
            @forelse ($recentNilai ?? [] as $nilai)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5">
                    <div class="w-2 h-2 bg-teal-300 rounded-full"></div>
                    <div>
                        <p class="font-medium text-slate-200">{{ $nilai->siswa->nama_siswa }} - {{ $nilai->nilai }}</p>
                        <p class="text-sm text-slate-400">{{ $nilai->tanggal_nilai->format('d M Y') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-slate-400 py-8">Belum ada nilai yang dimasukkan.</p>
            @endforelse
        </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
        <h3 class="text-2xl font-semibold mb-6">Quick Actions</h3>
        <div class="space-y-2">
            <a href="/absensi" class="block p-3 rounded-xl bg-white/5 hover:bg-white/10">Input Absensi</a>
            <a href="/nilai" class="block p-3 rounded-xl bg-white/5 hover:bg-white/10">Input Nilai</a>
            <a href="/rekap/nilai" class="block p-3 rounded-xl bg-white/5 hover:bg-white/10">Lihat Rekap</a>
        </div>
    </div>
</section>
