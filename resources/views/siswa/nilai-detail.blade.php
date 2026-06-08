@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Detail Nilai</p>
                <h2 class="mt-3 text-3xl font-semibold">{{ $mapel->nama_mapel }}</h2>
                <p class="mt-2 text-slate-300">Rincian nilai per jenis penilaian dan nilai akhir.</p>
            </div>
            <a href="{{ route('siswa.nilai.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15">
                Kembali ke Detail Nilai
            </a>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <div data-table-filter class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
            <div class="border-b border-white/10 px-6 py-4">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Rincian Nilai</h3>
                        <p class="text-sm text-slate-400">Cari berdasarkan tanggal, jenis, guru, atau keterangan.</p>
                    </div>
                    <input type="search" data-table-filter-input placeholder="Cari rincian nilai..." class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-slate-500 outline-none focus:border-teal-300/50 lg:max-w-sm">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10 text-left">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Tanggal</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Jenis</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Nilai</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Guru</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse ($nilaiDetail as $item)
                            <tr data-table-filter-row class="hover:bg-white/5">
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->tanggal_nilai?->format('d M Y') ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->jenisPenilaian?->nama_jenis ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-300">{{ number_format((float) $item->nilai, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->guru?->user?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-400">Belum ada detail nilai untuk mata pelajaran ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p data-table-filter-empty hidden class="px-6 py-5 text-center text-sm text-slate-400">Tidak ada data yang cocok dengan pencarian.</p>
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
                <h3 class="text-xl font-semibold mb-4">Ringkasan Nilai Akhir</h3>
                @if ($nilaiAkhirList->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($nilaiAkhirList as $nilaiAkhir)
                            <div class="rounded-2xl bg-white/5 p-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm text-slate-400">{{ $nilaiAkhir->kelas?->nama_kelas ?? '-' }} Semester {{ $nilaiAkhir->semester }}</p>
                                        <p class="text-sm text-slate-500">{{ $nilaiAkhir->tahunAjaran?->nama_tahun_ajaran ?? '-' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-teal-300">{{ number_format((float) $nilaiAkhir->nilai_akhir, 2) }}</p>
                                        <p class="text-sm text-slate-300">{{ $nilaiAkhir->predikat ?? '-' }}</p>
                                    </div>
                                </div>
                                <p class="mt-3 text-sm text-slate-300">{{ $nilaiAkhir->status_lulus ? 'Tuntas' : 'Perlu remedial' }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-slate-400">Belum ada nilai akhir untuk mata pelajaran ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
