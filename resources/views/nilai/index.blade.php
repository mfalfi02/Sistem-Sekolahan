@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Nilai</p>
                <h2 class="mt-3 text-3xl font-semibold">Input Nilai Siswa</h2>
                <p class="mt-2 text-slate-300">Pilih kelas, mapel, dan jenis penilaian untuk mengisi nilai seluruh siswa sekaligus.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15">
                Kembali
            </a>
        </div>
    </section>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
        <form method="GET" action="{{ route('nilai.index') }}" class="grid gap-4 lg:grid-cols-4">
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
                        <option value="{{ $mapel->id }}" @selected($selectedMapelId === $mapel->id)>{{ $mapel->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Jenis Penilaian</label>
                <select name="jenis_penilaian_id" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                    @foreach ($jenisList as $jenis)
                        <option value="{{ $jenis->id }}" @selected($selectedJenisPenilaianId === $jenis->id)>{{ $jenis->nama_jenis }} ({{ $jenis->bobot }}%)</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
            </div>
            <div class="lg:col-span-4 flex justify-end">
                <button class="rounded-2xl bg-teal-300 px-4 py-3 font-semibold text-slate-950 hover:bg-teal-200">
                    Tampilkan
                </button>
            </div>
        </form>
    </section>

    @if ($selectedKelas)
        <form method="POST" action="{{ route('nilai.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $selectedKelas->id }}">
            <input type="hidden" name="mata_pelajaran_id" value="{{ $selectedMapelId }}">
            <input type="hidden" name="jenis_penilaian_id" value="{{ $selectedJenisPenilaianId }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-6 shadow-2xl">
                <div class="grid gap-4 lg:grid-cols-3">
                    <div>
                        <p class="text-sm text-slate-400">Kelas aktif</p>
                        <p class="mt-1 text-lg font-semibold">{{ $selectedKelas->nama_kelas }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400">Tahun Ajaran</p>
                        <p class="mt-1 text-lg font-semibold">{{ $tahunAjaran?->nama_tahun_ajaran }} {{ $tahunAjaran?->semester }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400">Tanggal</p>
                        <p class="mt-1 text-lg font-semibold">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</p>
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10 text-left">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-200">Nama Siswa</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-200">NIS</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-200">Nilai</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-200">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse ($students as $student)
                                @php($row = $existingNilai[$student->id] ?? null)
                                <tr class="hover:bg-white/5">
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-100">{{ $student->nama_siswa }}</p>
                                        <p class="text-xs text-slate-400">{{ $student->kelas?->nama_kelas }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-300">{{ $student->nis }}</td>
                                    <td class="px-6 py-4">
                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="100"
                                            name="nilai[{{ $student->id }}][angka]"
                                            value="{{ old('nilai.'.$student->id.'.angka', $row?->nilai) }}"
                                            class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none placeholder:text-slate-500 focus:border-teal-300/50"
                                        >
                                    </td>
                                    <td class="px-6 py-4">
                                        <input
                                            type="text"
                                            name="nilai[{{ $student->id }}][keterangan]"
                                            value="{{ old('nilai.'.$student->id.'.keterangan', $row?->keterangan) }}"
                                            placeholder="Opsional"
                                            class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none placeholder:text-slate-500 focus:border-teal-300/50"
                                        >
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-400">
                                        Tidak ada siswa di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="flex justify-end">
                <button class="rounded-2xl bg-teal-300 px-6 py-3 font-semibold text-slate-950 hover:bg-teal-200">
                    Simpan Nilai
                </button>
            </div>
        </form>
    @endif
</div>
@endsection
