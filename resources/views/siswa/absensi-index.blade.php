@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Detail Absensi</p>
                <h2 class="mt-3 text-3xl font-semibold">Absensi Setiap Semester</h2>
                <p class="mt-2 text-slate-300">{{ $siswa?->nama_siswa ?? '-' }} - {{ $siswa?->kelas?->nama_kelas ?? '-' }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-teal-300 px-5 py-3 text-sm font-bold text-slate-950 shadow-lg shadow-teal-950/20 hover:bg-teal-200">
                Kembali ke Dashboard
            </a>
        </div>
    </section>

    @forelse ($periods as $period)
        @php
            $hadirCount = (int) $period['summary']->get('hadir', 0);
            $attendancePercent = $period['total'] > 0 ? round(($hadirCount / $period['total']) * 100, 1) : 0;
        @endphp
        <section data-table-filter class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
            <div class="border-b border-white/10 px-6 py-5">
                <div class="grid gap-5 xl:grid-cols-[1fr_auto] xl:items-center">
                    <div>
                        <p class="text-xs uppercase tracking-[0.24em] text-teal-200/70">Semester</p>
                        <h3 class="mt-2 text-2xl font-semibold">{{ $period['label'] }}</h3>
                        <p class="mt-1 text-sm text-slate-400">{{ $period['total'] }} catatan absensi</p>
                    </div>
                    <div class="grid gap-3 lg:grid-cols-[1.2fr_repeat(5,88px)] xl:w-[760px]">
                        <div class="rounded-2xl border border-teal-300/20 bg-teal-300/10 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-200/70">Kehadiran</p>
                            <div class="mt-2 flex items-end justify-between gap-3">
                                <p class="text-4xl font-bold text-teal-200">{{ $attendancePercent }}%</p>
                                <p class="text-sm font-semibold text-slate-300">{{ $hadirCount }}/{{ $period['total'] }}</p>
                            </div>
                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-950/50">
                                <div class="h-full rounded-full bg-teal-300" style="width: {{ min(100, max(0, $attendancePercent)) }}%"></div>
                            </div>
                        </div>
                        @foreach (['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alfa' => 'Alfa', 'terlambat' => 'Telat'] as $status => $label)
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-3 text-center">
                                <p class="text-2xl font-bold {{ $status === 'hadir' ? 'text-emerald-200' : ($status === 'terlambat' ? 'text-amber-200' : 'text-slate-100') }}">{{ $period['summary']->get($status, 0) }}</p>
                                <p class="mt-1 text-xs text-slate-400">{{ $label }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="border-b border-white/10 px-6 py-4">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-white">Tabel Absensi</h4>
                        <p class="text-sm text-slate-400">Cari berdasarkan tanggal, status, guru, atau keterangan.</p>
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
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Guru</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach ($period['records'] as $item)
                            <tr data-table-filter-row class="hover:bg-white/5">
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->tanggal_absen?->format('d M Y') ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-200">{{ ucfirst($item->status_kehadiran) }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->guru?->nama_guru ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->keterangan ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p data-table-filter-empty hidden class="px-6 py-5 text-center text-sm text-slate-400">Tidak ada data yang cocok dengan pencarian.</p>
        </section>
    @empty
        <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-10 text-center shadow-2xl">
            <p class="text-slate-400">Belum ada data absensi.</p>
        </section>
    @endforelse
</div>
@endsection
