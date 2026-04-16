@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Absensi</p>
                <h2 class="mt-3 text-3xl font-semibold">Input Absensi Siswa</h2>
                <p class="mt-2 text-slate-300">Pilih kelas dan tanggal, lalu isi kehadiran seluruh siswa dalam satu halaman.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15">
                Kembali
            </a>
        </div>
    </section>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
        <form method="GET" action="{{ route('absensi.index') }}" class="grid gap-4 lg:grid-cols-3">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Kelas</label>
                <select name="kelas_id" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected(optional($selectedKelas)->id === $kelas->id)>{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
            </div>
            <div class="flex items-end">
                <button class="w-full rounded-2xl bg-teal-300 px-4 py-3 font-semibold text-slate-950 hover:bg-teal-200">
                    Tampilkan
                </button>
            </div>
        </form>
    </section>

    @if ($selectedKelas)
        <form method="POST" action="{{ route('absensi.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
                <div class="grid gap-4 lg:grid-cols-3">
                    <div>
                        <p class="text-sm text-slate-400">Kelas aktif</p>
                        <p class="mt-1 text-lg font-semibold">{{ $selectedKelas->nama_kelas }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400">Tanggal</p>
                        <p class="mt-1 text-lg font-semibold">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400">Tahun Ajaran</p>
                        <p class="mt-1 text-lg font-semibold">
                            {{ $tahunAjaran?->nama_tahun_ajaran }} {{ $tahunAjaran?->semester }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
                <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Jadwal Aktif Hari Ini</h3>
                        <p class="text-sm text-slate-400">{{ $hariIni }}</p>
                    </div>
                    <p class="text-sm text-teal-300">
                        {{ $jadwalAktif->count() }} jadwal ditemukan
                    </p>
                </div>

                @if ($jadwalAktif->isNotEmpty())
                    <div class="mt-4">
                        <label class="mb-2 block text-sm font-medium text-slate-300" for="jadwal_id">Pilih Jadwal</label>
                        <select
                            id="jadwal_id"
                            name="jadwal_id"
                            class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50"
                        >
                            @foreach ($jadwalAktif as $jadwal)
                                <option value="{{ $jadwal->id }}" @selected(optional($selectedJadwal)->id === $jadwal->id)>
                                    {{ $jadwal->mataPelajaran?->nama_mapel }} - {{ $jadwal->guru?->nama_guru }} ({{ substr((string) $jadwal->jam_mulai, 0, 5) }} - {{ substr((string) $jadwal->jam_selesai, 0, 5) }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-slate-400">
                            Kalau hanya ada satu jadwal, sistem akan memilihnya otomatis.
                        </p>
                    </div>
                @else
                    <p class="mt-4 rounded-2xl border border-amber-500/20 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">
                        Belum ada jadwal aktif untuk kelas ini pada hari ini.
                    </p>
                @endif
            </section>

            <section class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10 text-left">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-200">Nama Siswa</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-200">NIS</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-200">Status</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-200">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse ($students as $student)
                                @php($row = $existingAbsensi[$student->id] ?? null)
                                <tr class="hover:bg-white/5">
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-100">{{ $student->nama_siswa }}</p>
                                        <p class="text-xs text-slate-400">{{ $student->kelas?->nama_kelas }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-300">{{ $student->nis }}</td>
                                    <td class="px-6 py-4">
                                        <select name="absensi[{{ $student->id }}][status]" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                                            @foreach (['hadir' => 'Hadir', 'sakit' => 'Sakit', 'izin' => 'Izin', 'alfa' => 'Alfa', 'terlambat' => 'Terlambat'] as $value => $label)
                                                <option value="{{ $value }}" @selected(old("absensi.$student->id.status", $row?->status_kehadiran ?? 'hadir') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input
                                            type="text"
                                            name="absensi[{{ $student->id }}][keterangan]"
                                            value="{{ old("absensi.$student->id.keterangan", $row?->keterangan) }}"
                                            placeholder="Opsional"
                                            class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none placeholder:text-slate-500 focus:border-teal-300/50"
                                        >
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-400">
                                        Tidak ada siswa di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="flex justify-end">
                <button class="rounded-2xl bg-teal-300 px-6 py-3 font-semibold text-slate-950 hover:bg-teal-200">
                    Simpan Absensi
                </button>
            </div>
        </form>
    @endif
</div>
