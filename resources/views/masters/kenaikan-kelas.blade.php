@extends('layouts.admin')

@section('admin_title', 'Kenaikan Kelas')

@section('content')
<div class="space-y-8">
    @php($autoTargetClass = $targetClassList->first())
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Kenaikan Kelas</p>
                <h2 class="mt-3 text-3xl font-semibold">Pembagian Kelas Awal Tahun Ajaran</h2>
                <p class="mt-2 text-slate-300">
                    Sistem membaca nilai akhir dari {{ $tahunAjaranAktif?->nama_tahun_ajaran ?? '-' }}.
                    Admin tetap bisa mengubah hasil rekomendasi sebelum siswa dipindah kelas.
                </p>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
        <form method="GET" action="{{ route('kenaikan-kelas.index') }}" class="grid gap-4 lg:grid-cols-3">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Kelas Asal</label>
                <select name="kelas_id" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected((int) ($selectedKelas?->id ?? 0) === $kelas->id)>{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full rounded-2xl bg-teal-300 px-5 py-3 font-semibold text-slate-950 hover:bg-teal-200">
                    Tampilkan Siswa
                </button>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Patokan KKM</p>
                <p class="mt-2 text-2xl font-semibold">{{ number_format($kkmAverage, 2) }}</p>
                <p class="text-sm text-slate-400">Rata-rata KKM mapel yang dijadikan ambang rekomendasi naik.</p>
            </div>
        </form>
    </section>

    <form method="POST" action="{{ route('kenaikan-kelas.store') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ $selectedKelas?->id }}">

        <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-sm text-slate-400">Cara pakai</p>
                    <p class="mt-2 text-sm text-slate-300">
                        Centang siswa yang ingin dipindah, lalu simpan. Sistem akan mengarahkan siswa ke kelas jenjang berikutnya secara otomatis.
                    </p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-sm text-slate-400">Kelas tujuan otomatis</p>
                    @if ($hasPromotionTarget)
                        <p class="mt-2 text-base font-semibold text-white">{{ $autoTargetClass?->nama_kelas ?? '-' }}</p>
                        <p class="mt-1 text-sm text-slate-300">Siswa yang dicentang akan dipindahkan ke kelas ini.</p>
                    @else
                        <p class="mt-2 text-base font-semibold text-rose-300">{{ $promotionBlockedMessage }}</p>
                    @endif
                </div>
            </div>

            <div data-table-filter class="mt-6 overflow-hidden rounded-3xl border border-white/10">
                <div class="flex flex-col gap-3 border-b border-white/10 bg-white/5 px-4 py-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-white">Daftar Siswa</h3>
                        <p class="text-sm text-slate-400">Cari siswa berdasarkan nama, NIS, atau rekomendasi.</p>
                    </div>
                    <input type="search" data-table-filter-input placeholder="Cari siswa..." class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-slate-500 outline-none focus:border-teal-300/50 lg:max-w-sm">
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10 text-left">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-4 py-4 text-sm font-semibold text-slate-200">Naik</th>
                                <th class="px-4 py-4 text-sm font-semibold text-slate-200">Siswa</th>
                        <th class="px-4 py-4 text-sm font-semibold text-slate-200">Kelas Saat Ini</th>
                        <th class="px-4 py-4 text-sm font-semibold text-slate-200">Rata-rata</th>
                        <th class="px-4 py-4 text-sm font-semibold text-slate-200">Tuntas</th>
                        <th class="px-4 py-4 text-sm font-semibold text-slate-200">Rekomendasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                            @forelse ($students as $row)
                                @php($student = $row['siswa'])
                                @php($checkedIds = array_map('intval', (array) old('naik_ids', $row['rekomendasi_naik'] ? [$student->id] : [])))
                                <tr data-table-filter-row class="hover:bg-white/5">
                                    <td class="px-4 py-4">
                                        <input
                                            type="checkbox"
                                            name="naik_ids[]"
                                            value="{{ $student->id }}"
                                            class="rounded border-white/20 bg-white/10 text-teal-400"
                                            @checked(in_array($student->id, $checkedIds))
                                        >
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-200">
                                        <p class="font-medium">{{ $student->nama_siswa }}</p>
                                        <p class="text-xs text-slate-400">{{ $student->nis }}</p>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-300">{{ $student->kelas?->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-300">{{ number_format($row['rata_rata'], 2) }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-300">{{ $row['tuntas'] }} / {{ $row['total_mapel'] }}</td>
                                    <td class="px-4 py-4 text-sm">
                                        @if ($row['rekomendasi_naik'])
                                            <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-emerald-300">Naik</span>
                                        @else
                                            <span class="rounded-full bg-rose-500/15 px-3 py-1 text-rose-300">Tidak Naik</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">
                                        Belum ada siswa di kelas ini atau nilai akhir belum tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p data-table-filter-empty hidden class="px-4 py-5 text-center text-sm text-slate-400">Tidak ada siswa yang cocok dengan pencarian.</p>
            </div>
        </section>

        <div class="flex">
            <button class="rounded-2xl bg-teal-300 px-5 py-3 font-semibold text-slate-950 hover:bg-teal-200 disabled:cursor-not-allowed disabled:opacity-60" @disabled(! $hasPromotionTarget)>
                Simpan Pembagian Kelas
            </button>
        </div>
    </form>
</div>
@endsection
