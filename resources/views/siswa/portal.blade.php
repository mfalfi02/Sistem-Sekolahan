@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Portal Siswa</p>
                <h2 class="mt-3 text-3xl font-semibold">Hasil Belajar Saya</h2>
                <p class="mt-2 text-slate-300">Lihat absensi, nilai akhir, dan jadwal hari ini.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15">
                Kembali
            </a>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
            <p class="text-sm text-slate-400">Nama</p>
            <p class="mt-2 text-2xl font-semibold">{{ $siswa?->nama_siswa ?? '-' }}</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
            <p class="text-sm text-slate-400">Kelas</p>
            <p class="mt-2 text-2xl font-semibold">{{ $siswa?->kelas?->nama_kelas ?? '-' }}</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
            <p class="text-sm text-slate-400">NIS</p>
            <p class="mt-2 text-2xl font-semibold">{{ $siswa?->nis ?? '-' }}</p>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <div data-table-filter class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
            <div class="border-b border-white/10 px-6 py-4">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Absensi Terakhir</h3>
                        <p class="text-sm text-slate-400">Cari absensi berdasarkan status atau keterangan.</p>
                    </div>
                    <input type="search" data-table-filter-input placeholder="Cari absensi..." class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-slate-500 outline-none focus:border-teal-300/50 lg:max-w-sm">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10 text-left">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Tanggal</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Status</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse ($absensi as $item)
                            <tr data-table-filter-row class="hover:bg-white/5">
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->tanggal_absen->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ ucfirst($item->status_kehadiran) }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-sm text-slate-400">Belum ada data absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p data-table-filter-empty hidden class="px-6 py-5 text-center text-sm text-slate-400">Tidak ada data yang cocok dengan pencarian.</p>
        </div>

        <div data-table-filter class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
            <div class="border-b border-white/10 px-6 py-4">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Nilai Akhir</h3>
                        <p class="text-sm text-slate-400">Cari nilai berdasarkan mapel atau predikat.</p>
                    </div>
                    <input type="search" data-table-filter-input placeholder="Cari nilai..." class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-slate-500 outline-none focus:border-teal-300/50 lg:max-w-sm">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10 text-left">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Mapel</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Nilai</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Predikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse ($nilaiAkhir as $item)
                            <tr data-table-filter-row class="hover:bg-white/5">
                                <td class="px-6 py-4 text-sm text-slate-300">
                                    <a href="{{ route('siswa.nilai.detail', $item->mata_pelajaran_id) }}" class="hover:text-teal-300 font-medium">
                                        {{ $item->mataPelajaran?->nama_mapel }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ number_format((float) $item->nilai_akhir, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->predikat ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-sm text-slate-400">Belum ada nilai akhir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p data-table-filter-empty hidden class="px-6 py-5 text-center text-sm text-slate-400">Tidak ada data yang cocok dengan pencarian.</p>
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
        <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h3 class="text-2xl font-semibold">Notifikasi Terbaru</h3>
                <p class="text-sm text-slate-400">Update dari sistem sekolah untuk akun Anda.</p>
            </div>
        </div>
        <div class="space-y-3">
            @forelse ($notifikasi ?? [] as $item)
                <a href="{{ $item->link ?? '#' }}" class="block rounded-2xl border border-white/10 bg-white/5 p-4 hover:bg-white/10">
                    <div class="flex flex-col gap-1 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="font-semibold text-slate-100">{{ $item->judul }}</p>
                            <p class="text-sm text-slate-300">{{ $item->pesan }}</p>
                        </div>
                        <div class="text-xs text-slate-400">
                            {{ $item->created_at->format('d M Y H:i') }}
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-center text-slate-400 py-8">Belum ada notifikasi.</p>
            @endforelse
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
        <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h3 class="text-2xl font-semibold">Jadwal Hari Ini</h3>
                <p class="text-sm text-slate-400">Pelajaran yang harus diikuti hari ini.</p>
            </div>
            <p class="text-sm text-teal-300">{{ $hariIni ?? 'Hari ini' }}</p>
        </div>
        <div class="space-y-3">
            @forelse ($jadwalHariIni ?? [] as $jadwal)
                <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                    <p class="text-sm uppercase tracking-[0.2em] text-slate-400">{{ $jadwal->mataPelajaran?->nama_mapel }}</p>
                    <p class="mt-1 font-semibold text-slate-100">{{ $jadwal->guru?->nama_guru }}</p>
                    <p class="text-sm text-slate-300">{{ substr((string) $jadwal->jam_mulai, 0, 5) }} - {{ substr((string) $jadwal->jam_selesai, 0, 5) }} · Ruang {{ $jadwal->ruang ?? '-' }}</p>
                </div>
            @empty
                <p class="text-center text-slate-400 py-8">Belum ada jadwal aktif hari ini.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
