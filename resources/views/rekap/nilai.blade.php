@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Rekap Nilai</p>
                <h2 class="mt-3 text-3xl font-semibold">Laporan Nilai Akhir</h2>
                <p class="mt-2 text-slate-300">Ringkasan nilai akhir per kelas dan mata pelajaran.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15">
                Kembali
            </a>
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
        <form method="GET" action="{{ route('rekap.nilai') }}" class="grid gap-4 lg:grid-cols-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Kelas</label>
                <select name="kelas_id" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected(optional($selectedKelas)->id === $kelas->id)>{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                    @foreach ($mapelList as $mapel)
                        <option value="{{ $mapel->id }}" @selected(optional($selectedMapel)->id === $mapel->id)>{{ $mapel->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal Dari</label>
                <input type="date" name="tanggal_dari" value="{{ $tanggalDari }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
            </div>
            <div class="flex items-end gap-2">
                <button class="flex-1 rounded-2xl bg-teal-300 px-4 py-3 font-semibold text-slate-950 hover:bg-teal-200">
                    Tampilkan
                </button>
                <a href="{{ route('rekap.nilai.export', request()->query()) }}" class="rounded-2xl bg-emerald-500 px-4 py-3 font-semibold text-white hover:bg-emerald-400">
                    Export Excel
                </a>
            </div>
        </form>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
            <p class="text-sm text-slate-400">Rata-rata Nilai</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($average, 2) }}</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
            <p class="text-sm text-slate-400">Kelas</p>
            <p class="mt-2 text-3xl font-semibold">{{ $selectedKelas?->nama_kelas ?? '-' }}</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
            <p class="text-sm text-slate-400">Mapel</p>
            <p class="mt-2 text-3xl font-semibold">{{ $selectedMapel?->nama_mapel ?? '-' }}</p>
        </div>
    </section>

    <section class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-left">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Peringkat</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Siswa</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Nilai Akhir</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Predikat</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse ($records as $index => $record)
                        <tr class="hover:bg-white/5">
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $record->siswa?->nama_siswa }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ number_format((float) $record->nilai_akhir, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $record->predikat ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $record->status_lulus ? 'Tuntas' : 'Belum Tuntas' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-400">Belum ada nilai akhir yang dihitung.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
