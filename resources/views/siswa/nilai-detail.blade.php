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
            <a href="{{ route('siswa.portal') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15">
                Kembali ke Portal
            </a>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <div class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
            <div class="border-b border-white/10 px-6 py-4">
                <h3 class="text-xl font-semibold">Rincian Nilai</h3>
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
                            <tr class="hover:bg-white/5">
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->tanggal_nilai->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->jenisPenilaian?->nama_jenis ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-300">{{ number_format((float) $item->nilai, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $item->guru?->user->name ?? '-' }}</td>
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
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
                <h3 class="text-xl font-semibold mb-4">Ringkasan Nilai Akhir</h3>
                @if ($nilaiAkhir)
                    <div class="grid grid-cols-2 gap-6 text-center">
                        <div>
                            <p class="text-3xl font-bold text-teal-300">{{ number_format((float) $nilaiAkhir->nilai_akhir, 2) }}</p>
                            <p class="text-sm text-slate-400 mt-1">Nilai Akhir</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold">{{ $nilaiAkhir->predikat }}</p>
                            <p class="text-sm text-slate-400 mt-1">Predikat</p>
                        </div>
                    </div>
                    <div class="mt-6 p-4 rounded-2xl bg-white/5">
                        <p class="text-slate-300">{{ $nilaiAkhir->status_lulus ? 'Tuntas' : 'Perlu remedial' }}</p>
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
