@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Rekap Absensi</p>
                <h2 class="mt-3 text-3xl font-semibold">Laporan Kehadiran Siswa</h2>
                <p class="mt-2 text-slate-300">Ringkasan absensi per kelas dan periode tertentu.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15">
                Kembali
            </a>
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
        <form method="GET" action="{{ route('rekap.absensi') }}" class="grid gap-4 lg:grid-cols-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Kelas</label>
                <select name="kelas_id" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected(optional($selectedKelas)->id === $kelas->id)>{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal Dari</label>
                <input type="date" name="tanggal_dari" value="{{ $tanggalDari }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal Sampai</label>
                <input type="date" name="tanggal_sampai" value="{{ $tanggalSampai }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
            </div>
            <div class="flex items-end gap-2">
                <button class="flex-1 rounded-2xl bg-teal-300 px-4 py-3 font-semibold text-slate-950 hover:bg-teal-200">
                    Tampilkan
                </button>
                <a href="{{ route('rekap.absensi.export', request()->query()) }}" class="rounded-2xl bg-emerald-500 px-4 py-3 font-semibold text-white hover:bg-emerald-400">
                    Export Excel
                </a>
            </div>
        </form>
    </section>

    <section class="grid gap-4 md:grid-cols-5">
        @foreach ($summary as $label => $value)
            <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
                <p class="text-sm text-slate-400">{{ strtoupper($label) }}</p>
                <p class="mt-2 text-3xl font-semibold">{{ $value }}</p>
            </div>
        @endforeach
    </section>

    <section class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-left">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Tanggal</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Siswa</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Kelas</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Status</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse ($records as $record)
                        <tr class="hover:bg-white/5">
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $record->tanggal_absen->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $record->siswa?->nama_siswa }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $record->kelas?->nama_kelas }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ ucfirst($record->status_kehadiran) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $record->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-400">Belum ada data absensi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
