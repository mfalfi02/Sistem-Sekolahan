@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Detail Nilai</p>
                <h2 class="mt-3 text-3xl font-semibold">Nilai Setiap Semester</h2>
                <p class="mt-2 text-slate-300">{{ $siswa?->nama_siswa ?? '-' }} - {{ $siswa?->kelas?->nama_kelas ?? '-' }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-teal-300 px-5 py-3 text-sm font-bold text-slate-950 shadow-lg shadow-teal-950/20 hover:bg-teal-200">
                Kembali ke Dashboard
            </a>
        </div>
    </section>

    @forelse ($periods as $period)
        <section data-table-filter class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
            <div class="border-b border-white/10 px-6 py-5">
                <div class="grid gap-5 xl:grid-cols-[1fr_auto] xl:items-center">
                    <div>
                        <p class="text-xs uppercase tracking-[0.24em] text-teal-200/70">Semester</p>
                        <h3 class="mt-2 text-2xl font-semibold">{{ $period['label'] }}</h3>
                        <p class="mt-1 text-sm text-slate-400">{{ $period['caption'] }}</p>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-4 xl:w-[620px]">
                        <div class="rounded-2xl border border-teal-300/20 bg-teal-300/10 p-4 sm:col-span-2">
                            <div class="flex items-end justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-200/70">Rata-rata</p>
                                    <p class="mt-2 text-4xl font-bold text-teal-200">{{ number_format((float) $period['average'], 2) }}</p>
                                </div>
                                <p class="text-sm font-semibold text-slate-300">/100</p>
                            </div>
                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-950/50">
                                <div class="h-full rounded-full bg-teal-300" style="width: {{ min(100, max(0, (float) $period['average'])) }}%"></div>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-xs text-slate-400">Tuntas</p>
                            <p class="mt-2 text-3xl font-bold text-emerald-200">{{ $period['tuntas'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-xs text-slate-400">Remedial</p>
                            <p class="mt-2 text-3xl font-bold text-amber-200">{{ $period['remedial'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-slate-300">{{ $period['records']->count() }} mapel</span>
                    <span class="rounded-full bg-emerald-300/10 px-3 py-1 text-xs font-semibold text-emerald-200">{{ $period['tuntas'] }} tuntas</span>
                    <span class="rounded-full bg-amber-300/10 px-3 py-1 text-xs font-semibold text-amber-200">{{ $period['remedial'] }} perlu remedial</span>
                </div>
            </div>
            <div class="border-b border-white/10 px-6 py-4">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-white">Tabel Nilai</h4>
                        <p class="text-sm text-slate-400">Cari berdasarkan mapel, predikat, atau status.</p>
                    </div>
                    <input type="search" data-table-filter-input placeholder="Cari nilai..." class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-slate-500 outline-none focus:border-teal-300/50 lg:max-w-sm">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10 text-left">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Mapel</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Nilai Akhir</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Predikat</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Status</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @foreach ($period['records'] as $item)
                            <tr data-table-filter-row class="hover:bg-white/5">
                                <td class="px-6 py-4 text-sm font-medium text-slate-200">{{ $item->mataPelajaran?->nama_mapel ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-teal-200">{{ number_format((float) $item->nilai_akhir, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->predikat ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->status_lulus ? 'Tuntas' : 'Perlu remedial' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('siswa.nilai.detail', $item->mata_pelajaran_id) }}" class="font-medium text-teal-200 hover:text-teal-100">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p data-table-filter-empty hidden class="px-6 py-5 text-center text-sm text-slate-400">Tidak ada data yang cocok dengan pencarian.</p>
        </section>
    @empty
        <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-10 text-center shadow-2xl">
            <p class="text-slate-400">Belum ada nilai akhir yang tersedia.</p>
        </section>
    @endforelse
</div>
@endsection
