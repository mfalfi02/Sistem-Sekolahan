@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Absensi</p>
                <h2 class="mt-3 text-3xl font-semibold">Input Absensi Siswa</h2>
                <p class="mt-2 max-w-2xl text-slate-300">
                    Pilih kelas dan tanggal, lalu isi kehadiran siswa dalam tampilan yang lebih rapi, ringkas, dan mudah dibaca.
                </p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Kelas Aktif</p>
                    <p class="mt-1 text-lg font-semibold text-slate-100">{{ $selectedKelas?->nama_kelas ?? 'Belum dipilih' }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Tanggal</p>
                    <p class="mt-1 text-lg font-semibold text-slate-100">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <section class="grid gap-6 lg:grid-cols-[1fr_0.72fr]">
        <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
            <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-xl font-semibold">Filter Absensi</h3>
                    <p class="text-sm text-slate-400">Tentukan kelas dan tanggal untuk memuat daftar siswa.</p>
                </div>
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs uppercase tracking-[0.2em] text-slate-300">
                    Form utama
                </span>
            </div>

            <form method="GET" action="{{ route('absensi.index') }}" class="mt-6 grid gap-4 lg:grid-cols-[1.15fr_0.85fr_auto] lg:items-end">
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
                <div>
                    <button class="w-full rounded-2xl bg-teal-300 px-4 py-3 font-semibold text-slate-950 hover:bg-teal-200">
                        Tampilkan
                    </button>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
            <p class="text-sm font-medium text-slate-400">Ringkasan Cepat</p>
            <div class="mt-4 grid gap-3">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Kelas Aktif</p>
                    <p class="mt-1 text-lg font-semibold text-slate-100">{{ $selectedKelas?->nama_kelas ?? '-' }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Siswa</p>
                    <p class="mt-1 text-lg font-semibold text-slate-100">{{ $students->count() }} siswa</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Jadwal Hari Ini</p>
                    <p class="mt-1 text-lg font-semibold text-slate-100">{{ $jadwalAktif->count() }} jadwal</p>
                </div>
            </div>
        </div>
    </section>

    @if ($selectedKelas)
        <form method="POST" action="{{ route('absensi.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            @php
                $statusStyles = [
                    'hadir' => ['label' => 'Hadir', 'class' => 'border-emerald-500/20 bg-emerald-500/10 text-emerald-200'],
                    'sakit' => ['label' => 'Sakit', 'class' => 'border-amber-500/20 bg-amber-500/10 text-amber-200'],
                    'izin' => ['label' => 'Izin', 'class' => 'border-sky-500/20 bg-sky-500/10 text-sky-200'],
                    'alfa' => ['label' => 'Alfa', 'class' => 'border-rose-500/20 bg-rose-500/10 text-rose-200'],
                    'terlambat' => ['label' => 'Terlambat', 'class' => 'border-orange-500/20 bg-orange-500/10 text-orange-200'],
                ];
            @endphp

            <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Jadwal Aktif Hari Ini</h3>
                        <p class="text-sm text-slate-400">{{ $hariIni }}</p>
                    </div>
                    <div class="flex flex-wrap gap-3 text-sm">
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-slate-200">
                            {{ $jadwalAktif->count() }} jadwal ditemukan
                        </span>
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-slate-200">
                            {{ $students->count() }} siswa
                        </span>
                    </div>
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
                <div class="border-b border-white/10 px-6 py-5">
                    <div class="flex flex-col gap-2 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <h3 class="text-xl font-semibold">Daftar Kehadiran</h3>
                            <p class="text-sm text-slate-400">Isi status dan keterangan per siswa dari tabel di bawah.</p>
                        </div>
                        <p class="text-sm text-slate-400">
                            Tanggal <span class="text-slate-100">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</span>
                        </p>
                    </div>
                </div>
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
                                @php($currentStatus = $row?->status_kehadiran ?? 'hadir')
                                <tr class="transition hover:bg-white/5">
                                    <td class="px-6 py-5 align-top">
                                        <p class="font-medium text-slate-100">{{ $student->nama_siswa }}</p>
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ $student->kelas?->nama_kelas }}</p>
                                    </td>
                                    <td class="px-6 py-5 align-top text-sm text-slate-300">{{ $student->nis }}</td>
                                    <td class="px-6 py-5 align-top">
                                        <div class="space-y-3">
                                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] {{ $statusStyles[$currentStatus]['class'] ?? 'border-white/10 bg-white/5 text-slate-200' }}">
                                                {{ $statusStyles[$currentStatus]['label'] ?? 'Hadir' }}
                                            </span>
                                            <select name="absensi[{{ $student->id }}][status]" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                                                @foreach ($statusStyles as $value => $meta)
                                                    <option value="{{ $value }}" @selected(old("absensi.$student->id.status", $currentStatus) === $value)>{{ $meta['label'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <input
                                            type="text"
                                            name="absensi[{{ $student->id }}][keterangan]"
                                            value="{{ old("absensi.$student->id.keterangan", $row?->keterangan) }}"
                                            placeholder="Opsional"
                                            class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none placeholder:text-slate-500 focus:border-teal-300/50"
                                        >
                                        <p class="mt-2 text-xs text-slate-500">Catatan singkat jika diperlukan.</p>
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
