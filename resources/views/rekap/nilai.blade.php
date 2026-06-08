@extends(in_array(auth()->user()?->role, ['admin', 'tu'], true) ? 'layouts.admin' : 'layouts.app')

@section('admin_title', 'Laporan Nilai')

@section('content')
<div class="space-y-5 md:space-y-6">
    <section class="overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 shadow-2xl">
        <div class="grid gap-6 px-5 py-6 md:px-8 md:py-8 xl:grid-cols-[1.2fr_0.8fr] xl:items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.32em] text-teal-200/70">Rekap Nilai</p>
                <h2 class="mt-3 text-2xl font-semibold text-white md:text-4xl">Laporan Nilai Akhir</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300 md:text-base">
                    Ringkasan nilai akhir per kelas dan mata pelajaran dengan tampilan yang lebih rapi untuk memantau hasil belajar dengan cepat.
                </p>
            </div>
            <div class="grid gap-3 sm:grid-cols-3 xl:grid-cols-1">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Fokus</p>
                    <p class="mt-2 text-lg font-semibold text-white">Hasil Akhir</p>
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
        <form method="GET" action="{{ route('rekap.nilai') }}" class="grid gap-4 xl:grid-cols-[1fr_1fr_1fr_auto]">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Kelas</label>
                <select name="kelas_id" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-teal-300/50">
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected(optional($selectedKelas)->id === $kelas->id)>{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-teal-300/50">
                    @foreach ($mapelList as $mapel)
                        <option value="{{ $mapel->id }}" @selected(optional($selectedMapel)->id === $mapel->id)>{{ $mapel->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal Dari</label>
                <input type="date" name="tanggal_dari" value="{{ $tanggalDari }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-teal-300/50">
            </div>
            <div class="flex items-end gap-2 xl:justify-end">
                <button class="flex-1 rounded-2xl bg-teal-300 px-4 py-3 font-semibold text-slate-950 transition hover:bg-teal-200 xl:flex-none">
                    Tampilkan
                </button>
                <a href="{{ route('rekap.nilai.export', request()->query()) }}" class="rounded-2xl bg-emerald-500 px-4 py-3 font-semibold text-white transition hover:bg-emerald-400 flex items-center justify-center gap-2 xl:flex-none">
                    Excel
                </a>
                <a href="{{ route('rekap.nilai.export-pdf', request()->query()) }}" class="rounded-2xl bg-red-500 px-4 py-3 font-semibold text-white transition hover:bg-red-400 flex items-center justify-center gap-2 xl:flex-none">
                    PDF
                </a>
            </div>
        </form>
    </section>

    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Rata-rata Nilai</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ number_format($average, 2) }}</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Kelas</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $selectedKelas?->nama_kelas ?? '-' }}</p>
        </div>
        <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Mapel</p>
            <p class="mt-2 text-3xl font-semibold text-white">{{ $selectedMapel?->nama_mapel ?? '-' }}</p>
        </div>
    </section>

    <section data-table-filter class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
        <div class="border-b border-white/10 px-5 py-4 md:px-6">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-white">Tabel Nilai Akhir</h3>
                    <p class="text-sm text-slate-400">Cari berdasarkan nama siswa, predikat, atau status.</p>
                </div>
                <input
                    type="search"
                    data-table-filter-input
                    placeholder="Cari nilai akhir..."
                    class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-slate-500 outline-none focus:border-teal-300/50 lg:max-w-sm"
                >
            </div>
        </div>
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
                        <tr data-table-filter-row class="hover:bg-white/5">
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
        <p data-table-filter-empty hidden class="px-6 py-5 text-center text-sm text-slate-400">Tidak ada data yang cocok dengan pencarian.</p>
    </section>
</div>
@endsection
