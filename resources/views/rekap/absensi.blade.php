@extends(in_array(auth()->user()?->role, ['admin', 'tu'], true) ? 'layouts.admin' : 'layouts.app')

@section('admin_title', 'Laporan Absensi')

@section('content')
<div class="space-y-5 md:space-y-6">
    <section class="overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 shadow-2xl">
        <div class="grid gap-6 px-5 py-6 md:px-8 md:py-8 xl:grid-cols-[1.2fr_0.8fr] xl:items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.32em] text-teal-200/70">Rekap Absensi</p>
                <h2 class="mt-3 text-2xl font-semibold text-white md:text-4xl">Laporan Kehadiran</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300 md:text-base">
                    Ringkasan kehadiran per kelas dan periode dengan tampilan yang lebih jelas, cepat dipindai, dan nyaman untuk pengawasan.
                </p>
            </div>
            <div class="grid gap-3 sm:grid-cols-3 xl:grid-cols-1">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Fokus</p>
                    <p class="mt-2 text-lg font-semibold text-white">Kehadiran Siswa</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Periode</p>
                    <p class="mt-2 text-lg font-semibold text-white">Per Kelas</p>
                </div>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-4 text-sm font-medium text-white transition hover:bg-white/10">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl md:p-6">
        <form method="GET" action="{{ route('rekap.absensi') }}" class="grid gap-3 md:grid-cols-2 xl:grid-cols-[1fr_1fr_1fr_auto]">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Kelas</label>
                <select name="kelas_id" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-teal-300/50">
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected(optional($selectedKelas)->id === $kelas->id)>{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Dari</label>
                <input type="date" name="tanggal_dari" value="{{ $tanggalDari }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-teal-300/50">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Sampai</label>
                <input type="date" name="tanggal_sampai" value="{{ $tanggalSampai }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-teal-300/50">
            </div>
            <div class="flex items-end gap-2 xl:justify-end">
                <button class="flex-1 rounded-2xl bg-teal-300 px-4 py-3 font-semibold text-slate-950 transition hover:bg-teal-200 xl:flex-none">
                    Filter
                </button>
                <a href="{{ route('rekap.absensi.export', request()->query()) }}" class="flex-1 text-center rounded-2xl bg-emerald-500 px-4 py-3 font-semibold text-white transition hover:bg-emerald-400 flex items-center justify-center gap-2 xl:flex-none">
                    Excel
                </a>
                <a href="{{ route('rekap.absensi.export-pdf', request()->query()) }}" class="flex-1 text-center rounded-2xl bg-red-500 px-4 py-3 font-semibold text-white transition hover:bg-red-400 flex items-center justify-center gap-2 xl:flex-none">
                    PDF
                </a>
            </div>
        </form>
    </section>

    <div class="grid gap-3 sm:gap-4 md:grid-cols-5">
        @foreach ($summary as $label => $value)
            <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-400 truncate">{{ strtoupper($label) }}</p>
                <p class="mt-2 text-2xl font-semibold text-white">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <section data-table-filter class="rounded-3xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl md:p-6">
        <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white">Data Absensi</h3>
                <p class="text-sm text-slate-400">Cari berdasarkan siswa, kelas, status, atau keterangan.</p>
            </div>
            <div class="w-full md:max-w-sm">
                <input
                    type="search"
                    data-table-filter-input
                    placeholder="Cari data absensi..."
                    class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-slate-500 outline-none focus:border-teal-300/50"
                >
            </div>
        </div>
        <div class="hidden overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 md:block">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-white/10 text-left">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-slate-200 text-xs">Tanggal</th>
                            <th class="px-4 py-3 font-semibold text-slate-200 text-xs">Siswa</th>
                            <th class="px-4 py-3 font-semibold text-slate-200 text-xs">Kelas</th>
                            <th class="px-4 py-3 font-semibold text-slate-200 text-xs">Status</th>
                            <th class="px-4 py-3 font-semibold text-slate-200 text-xs">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse ($records as $record)
                            <tr data-table-filter-row class="hover:bg-white/5">
                                <td class="px-4 py-3 text-xs text-slate-300 whitespace-nowrap">{{ $record->tanggal_absen->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-xs text-slate-300">{{ $record->siswa?->nama_siswa }}</td>
                                <td class="px-4 py-3 text-xs text-slate-300 whitespace-nowrap">{{ $record->kelas?->nama_kelas }}</td>
                                <td class="px-4 py-3 text-xs text-slate-300 whitespace-nowrap">{{ ucfirst($record->status_kehadiran) }}</td>
                                <td class="px-4 py-3 text-xs text-slate-300">{{ $record->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-xs text-slate-400">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p data-table-filter-empty hidden class="px-4 py-5 text-center text-xs text-slate-400">Tidak ada data yang cocok dengan pencarian.</p>
        </div>

        <div class="md:hidden space-y-2">
            @forelse ($records as $record)
                <div data-table-filter-row class="rounded-lg border border-white/10 bg-slate-900/60 p-3 shadow">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <p class="text-slate-400">Tanggal</p>
                            <p class="font-medium text-slate-100">{{ $record->tanggal_absen->format('d M') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Status</p>
                            <p class="font-medium text-slate-100">{{ ucfirst($record->status_kehadiran) }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-slate-400">Siswa</p>
                            <p class="font-medium text-slate-100 truncate">{{ $record->siswa?->nama_siswa }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-slate-400">Kelas</p>
                            <p class="font-medium text-slate-100">{{ $record->kelas?->nama_kelas }}</p>
                        </div>
                        @if ($record->keterangan)
                            <div class="col-span-2">
                                <p class="text-slate-400">Keterangan</p>
                                <p class="font-medium text-slate-100">{{ $record->keterangan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4 text-center text-sm text-slate-400">
                    Belum ada data absensi pada periode ini.
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
