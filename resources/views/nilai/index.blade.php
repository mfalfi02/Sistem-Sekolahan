@extends(in_array(auth()->user()?->role, ['admin', 'tu'], true) ? 'layouts.admin' : 'layouts.app')

@section('admin_title', 'Penilaian')

@section('content')
<div class="space-y-5 md:space-y-6">
    <section class="overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 shadow-2xl">
        <div class="grid gap-6 px-5 py-6 md:px-8 md:py-8 xl:grid-cols-[1.3fr_0.7fr] xl:items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.32em] text-teal-200/70">Nilai Guru</p>
                <h2 class="mt-3 text-2xl font-semibold text-white md:text-4xl">Input Nilai Siswa</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300 md:text-base">
                    Pilih kelas, mata pelajaran, dan jenis penilaian untuk mengisi nilai seluruh siswa dengan tampilan yang lebih terstruktur dan mudah dibaca.
                </p>
            </div>
            <div class="grid gap-3 sm:grid-cols-3 xl:grid-cols-1">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Mode</p>
                    <p class="mt-2 text-lg font-semibold text-white">Input Cepat</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Fokus</p>
                    <p class="mt-2 text-lg font-semibold text-white">Nilai Harian</p>
                </div>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-4 text-sm font-medium text-white transition hover:bg-white/10">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl md:p-6">
        <form method="GET" action="{{ route('nilai.index') }}" class="grid gap-4 lg:grid-cols-4">
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
                        <option value="{{ $mapel->id }}" @selected($selectedMapelId === $mapel->id)>{{ $mapel->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Jenis Penilaian</label>
                <select name="jenis_penilaian_id" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-teal-300/50">
                    @foreach ($jenisList as $jenis)
                        <option value="{{ $jenis->id }}" @selected($selectedJenisPenilaianId === $jenis->id)>{{ $jenis->nama_jenis }} ({{ $jenis->bobot }}%)</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-teal-300/50">
            </div>
            <div class="lg:col-span-4 flex justify-end">
                <button class="w-full rounded-2xl bg-teal-300 px-5 py-3 font-semibold text-slate-950 transition hover:bg-teal-200 md:w-auto">
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

            <section class="grid gap-4 md:grid-cols-3">
                <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-5 shadow-2xl">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Kelas aktif</p>
                    <p class="mt-2 text-xl font-semibold text-white">{{ $selectedKelas->nama_kelas }}</p>
                    <p class="mt-1 text-sm text-slate-400">{{ $students->count() }} siswa terdaftar</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-5 shadow-2xl">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Tahun ajaran</p>
                    <p class="mt-2 text-xl font-semibold text-white">{{ $tahunAjaran?->nama_tahun_ajaran }} {{ $tahunAjaran?->semester }}</p>
                    <p class="mt-1 text-sm text-slate-400">Sinkron dengan semester aktif</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-5 shadow-2xl">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Tanggal input</p>
                    <p class="mt-2 text-xl font-semibold text-white">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</p>
                    <p class="mt-1 text-sm text-slate-400">Pencatatan nilai hari ini</p>
                </div>
            </section>

            <section data-table-filter class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
                <div class="border-b border-white/10 px-5 py-4 md:px-6">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-white">Data Nilai Siswa</h3>
                            <p class="text-sm text-slate-400">Cari berdasarkan nama atau NIS sebelum mengisi angka nilai.</p>
                        </div>
                        <input type="search" data-table-filter-input placeholder="Cari siswa..." class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-slate-500 outline-none focus:border-teal-300/50 lg:max-w-sm">
                    </div>
                </div>
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
                                <tr data-table-filter-row class="hover:bg-white/5">
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-white">{{ $student->nama_siswa }}</p>
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
                <p data-table-filter-empty hidden class="px-6 py-5 text-center text-sm text-slate-400">Tidak ada siswa yang cocok dengan pencarian.</p>
            </section>

            <div class="flex justify-end">
                <button class="w-full rounded-2xl bg-emerald-500 px-6 py-3 font-semibold text-white transition hover:bg-emerald-400 md:w-auto">
                    Simpan Nilai
                </button>
            </div>
        </form>
    @endif
</div>
@endsection
