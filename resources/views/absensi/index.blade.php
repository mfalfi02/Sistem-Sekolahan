@extends(in_array(auth()->user()?->role, ['admin', 'tu'], true) ? 'layouts.admin' : 'layouts.app')

@section('admin_title', 'Absensi')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 py-4 md:py-6">
    <div class="space-y-5 md:space-y-6">
        <section class="overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 shadow-2xl">
            <div class="grid gap-6 px-5 py-6 md:px-8 md:py-8 xl:grid-cols-[1.3fr_0.7fr] xl:items-center">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.32em] text-teal-200/70">Absensi Guru</p>
                    <h1 class="mt-3 text-2xl font-bold text-white md:text-4xl">Input Kehadiran Siswa</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300 md:text-base">
                        Pilih kelas dan tanggal, lalu isi kehadiran siswa dengan tampilan yang lebih ringkas, fleksibel, dan nyaman dipakai di desktop maupun mobile.
                    </p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Mode</p>
                        <p class="mt-2 text-lg font-semibold text-white">Input Harian</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Akses</p>
                        <p class="mt-2 text-lg font-semibold text-white">Guru Pengampu</p>
                    </div>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                ✓ {{ session('success') }}
            </div>
        @endif

        <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl md:p-6">
            <form method="GET" action="{{ route('absensi.index') }}" class="grid gap-4 lg:grid-cols-[1fr_1fr_auto]">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Pilih Kelas</label>
                    <select name="kelas_id" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-teal-300/50">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" @selected(optional($selectedKelas)->id === $kelas->id)>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-teal-300/50">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-2xl bg-teal-300 px-5 py-3 font-semibold text-slate-950 transition hover:bg-teal-200">
                        Tampilkan Data
                    </button>
                </div>
            </form>
        </section>

        @if ($selectedKelas)
            <section class="grid gap-4 md:grid-cols-3">
                <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Kelas aktif</p>
                    <p class="mt-2 text-xl font-semibold text-white">{{ $selectedKelas->nama_kelas }}</p>
                    <p class="mt-1 text-sm text-slate-400">{{ $students->count() }} siswa terdaftar</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Tanggal</p>
                    <p class="mt-2 text-xl font-semibold text-white">{{ \App\Support\IndonesianDateTime::date($tanggal) }}</p>
                    <p class="mt-1 text-sm text-slate-400">{{ $hariIni }}</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Tahun ajaran</p>
                    <p class="mt-2 text-xl font-semibold text-white">{{ $tahunAjaran?->nama_tahun_ajaran ?? '-' }}</p>
                    <p class="mt-1 text-sm text-slate-400">{{ $jadwalAktif->count() }} jadwal aktif hari ini</p>
                </div>
            </section>

            @if ($jadwalAktif->isNotEmpty())
                <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl md:p-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-white">Pilih Jadwal Hari Ini</h3>
                            <p class="mt-1 text-sm text-slate-400">{{ $jadwalAktif->count() }} jadwal tersedia untuk {{ $hariIni }}.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300">
                            Fokus kelas: <span class="font-semibold text-white">{{ $selectedKelas->nama_kelas }}</span>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('absensi.index') }}" class="mt-5 grid gap-3 lg:grid-cols-[1fr_auto]">
                        <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                        <select name="jadwal_id" id="jadwal_id" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-teal-300/50">
                            @foreach ($jadwalAktif as $jadwal)
                                <option value="{{ $jadwal->id }}" @selected(optional($selectedJadwal)->id === $jadwal->id)>
                                    {{ $jadwal->mataPelajaran?->nama_mapel }} - {{ $jadwal->guru?->nama_guru }} ({{ \App\Support\IndonesianDateTime::timeRange($jadwal->jam_mulai, $jadwal->jam_selesai) }})
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="rounded-2xl bg-blue-500 px-5 py-3 font-semibold text-white transition hover:bg-blue-400">
                            Pilih Jadwal
                        </button>
                    </form>
                </section>
            @else
                <div class="rounded-2xl border border-amber-500/30 bg-amber-500/10 p-4 text-sm text-amber-200">
                    Belum ada jadwal aktif untuk kelas ini pada hari ini.
                </div>
            @endif

            <form method="POST" action="{{ route('absensi.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <input type="hidden" name="jadwal_id" value="{{ $selectedJadwal?->id }}">

                <div data-table-filter class="space-y-4">
                    <div class="flex flex-col gap-3 rounded-3xl border border-white/10 bg-slate-900/60 p-4 md:flex-row md:items-center md:justify-between md:p-5">
                        <div>
                            <h3 class="text-base font-semibold text-white">Data Siswa</h3>
                            <p class="text-sm text-slate-400">Cari siswa berdasarkan nama atau NIS sebelum mengisi status kehadiran.</p>
                        </div>
                        <input type="search" data-table-filter-input placeholder="Cari siswa..." class="w-full md:max-w-sm rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-slate-500 outline-none transition focus:border-teal-300/50">
                    </div>

                    <div data-absensi-controls="desktop" class="hidden overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 md:block">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-white/5">
                                    <tr class="border-b border-white/10">
                                        <th class="px-4 py-3 text-left font-semibold text-white">Nama Siswa</th>
                                        <th class="px-4 py-3 text-left font-semibold text-white">NIS</th>
                                        <th class="px-4 py-3 text-left font-semibold text-white">Status Kehadiran</th>
                                        <th class="px-4 py-3 text-left font-semibold text-white">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/10">
                                    @forelse ($students as $student)
                                        @php($row = $existingAbsensi[$student->id] ?? null)
                                        <tr data-table-filter-row class="transition hover:bg-white/5">
                                            <td class="px-4 py-3 text-white">
                                                <div class="font-medium">{{ $student->nama_siswa }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-slate-300">{{ $student->nis }}</td>
                                            <td class="px-4 py-3">
                                                <select name="absensi[{{ $student->id }}][status]" class="w-36 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs text-white outline-none focus:border-teal-300/50">
                                                    @foreach (['hadir' => 'Hadir', 'sakit' => 'Sakit', 'izin' => 'Izin', 'alfa' => 'Alfa', 'terlambat' => 'Terlambat'] as $value => $label)
                                                        <option value="{{ $value }}" @selected(old("absensi.$student->id.status", $row?->status_kehadiran ?? 'hadir') === $value)>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" name="absensi[{{ $student->id }}][keterangan]" value="{{ old("absensi.$student->id.keterangan", $row?->keterangan) }}" placeholder="Opsional" class="w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs text-white placeholder-slate-500 outline-none focus:border-teal-300/50">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-slate-400">Tidak ada siswa di kelas ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div data-absensi-controls="mobile" class="space-y-3 md:hidden">
                        @forelse ($students as $student)
                            @php($row = $existingAbsensi[$student->id] ?? null)
                            <div data-table-filter-row class="rounded-2xl border border-white/10 bg-slate-900/60 p-4 shadow">
                                <div class="mb-3">
                                    <p class="font-semibold text-white">{{ $student->nama_siswa }}</p>
                                    <p class="mt-1 text-xs text-slate-400">NIS: {{ $student->nis }}</p>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <label class="mb-1 block text-xs text-slate-400">Status</label>
                                        <select name="absensi[{{ $student->id }}][status]" class="w-full rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white outline-none focus:border-teal-300/50">
                                            @foreach (['hadir' => 'Hadir', 'sakit' => 'Sakit', 'izin' => 'Izin', 'alfa' => 'Alfa', 'terlambat' => 'Terlambat'] as $value => $label)
                                                <option value="{{ $value }}" @selected(old("absensi.$student->id.status", $row?->status_kehadiran ?? 'hadir') === $value)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-slate-400">Keterangan (Opsional)</label>
                                        <input type="text" name="absensi[{{ $student->id }}][keterangan]" value="{{ old("absensi.$student->id.keterangan", $row?->keterangan) }}" placeholder="Catatan..." class="w-full rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white placeholder-slate-500 outline-none focus:border-teal-300/50">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-5 text-center text-slate-400">
                                Tidak ada siswa di kelas ini.
                            </div>
                        @endforelse
                    </div>

                    <div data-table-filter-empty hidden class="rounded-2xl border border-white/10 bg-slate-900/60 p-4 text-center text-sm text-slate-400">
                        Tidak ada siswa yang cocok dengan pencarian.
                    </div>
                </div>

                <div class="flex justify-end pt-2 md:pt-4">
                    <button type="submit" class="w-full rounded-2xl bg-emerald-500 px-6 py-3 font-semibold text-white transition hover:bg-emerald-400 md:w-auto">
                        ✓ Simpan Absensi
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
<script>
    (function () {
        const desktopQuery = window.matchMedia('(min-width: 768px)');
        const wrappers = Array.from(document.querySelectorAll('[data-absensi-controls]'));

        const syncDisabledState = () => {
            const activeLayout = desktopQuery.matches ? 'desktop' : 'mobile';

            wrappers.forEach((wrapper) => {
                const isActive = wrapper.dataset.absensiControls === activeLayout;

                wrapper.querySelectorAll('input, select, textarea, button').forEach((control) => {
                    control.disabled = !isActive;
                });
            });
        };

        syncDisabledState();

        if (typeof desktopQuery.addEventListener === 'function') {
            desktopQuery.addEventListener('change', syncDisabledState);
        } else if (typeof desktopQuery.addListener === 'function') {
            desktopQuery.addListener(syncDisabledState);
        }
    })();
</script>
@endsection
