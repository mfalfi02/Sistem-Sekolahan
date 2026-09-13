@extends(in_array(auth()->user()?->role, ['admin', 'tu', 'kepala_sekolah'], true) ? 'layouts.admin' : 'layouts.app')

@section('admin_title', 'Laporan Absensi')

@section('content')
<div class="space-y-5 md:space-y-6">
    <section class="overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 shadow-2xl">
        <div class="grid gap-6 px-5 py-6 md:px-8 md:py-8 xl:grid-cols-[1.2fr_0.8fr] xl:items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.32em] text-teal-200/70">Rekap Absensi</p>
                <h2 class="mt-3 text-2xl font-semibold text-white md:text-4xl">Laporan Kehadiran</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300 md:text-base">
                    Ringkasan kehadiran per kelas, mapel, dan jadwal dengan tampilan yang lebih jelas, cepat dipindai, dan nyaman untuk pengawasan.
                </p>
                <p class="mt-2 text-sm text-teal-200/80">Data absensi ini juga dipakai sebagai 10% komponen nilai akhir per mapel.</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Fokus</p>
                    <p class="mt-2 text-lg font-semibold text-white">Kehadiran Siswa</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Periode</p>
                    <p class="mt-2 text-lg font-semibold text-white">Per Kelas</p>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl md:p-6">
        <form method="GET" action="{{ route('rekap.absensi') }}" class="grid gap-3 md:grid-cols-2 xl:grid-cols-[1fr_1fr_1fr_1fr_1fr_auto]">
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
                    <option value="">Semua Mapel</option>
                    @foreach ($mapelList as $mapel)
                        <option value="{{ $mapel->id }}" @selected(optional($selectedMapel)->id === $mapel->id)>{{ $mapel->nama_mapel }}</option>
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
                <a href="#" data-export-type="excel" data-export-url="{{ route('rekap.absensi.export', request()->query()) }}" class="export-btn flex-1 text-center rounded-2xl bg-emerald-500 px-4 py-3 font-semibold text-white transition hover:bg-emerald-400 flex items-center justify-center gap-2 xl:flex-none">
                    Excel
                </a>
                <a href="#" data-export-type="pdf" data-export-url="{{ route('rekap.absensi.export-pdf', request()->query()) }}" class="export-btn flex-1 text-center rounded-2xl bg-red-500 px-4 py-3 font-semibold text-white transition hover:bg-red-400 flex items-center justify-center gap-2 xl:flex-none">
                    PDF
                </a>
            </div>
        </form>
    </section>

    @if ($selectedMapel || $selectedJadwal)
        <section class="grid gap-4 md:grid-cols-2">
            @if ($selectedMapel)
                <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-5 shadow-2xl">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Filter Mapel</p>
                    <p class="mt-2 text-xl font-semibold text-white">{{ $selectedMapel->nama_mapel }}</p>
                </div>
            @endif
            @if ($selectedJadwal)
                <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-5 shadow-2xl">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Filter Jadwal</p>
                    <p class="mt-2 text-xl font-semibold text-white">{{ $selectedJadwal->hari }} {{ \App\Support\IndonesianDateTime::timeRange($selectedJadwal->jam_mulai, $selectedJadwal->jam_selesai) }}</p>
                    <p class="mt-1 text-sm text-slate-400">{{ $selectedJadwal->guru?->nama_guru ?? '-' }}</p>
                </div>
            @endif
        </section>
    @endif

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
                <p class="text-sm text-slate-400">Cari berdasarkan siswa, kelas, mapel, jadwal, status, atau keterangan.</p>
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
                            <th class="px-4 py-3 font-semibold text-slate-200 text-xs">Mapel</th>
                            <th class="px-4 py-3 font-semibold text-slate-200 text-xs">Jadwal</th>
                            <th class="px-4 py-3 font-semibold text-slate-200 text-xs">Tahun Ajaran</th>
                            <th class="px-4 py-3 font-semibold text-slate-200 text-xs">Status</th>
                            <th class="px-4 py-3 font-semibold text-slate-200 text-xs">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse ($records as $record)
                            <tr data-table-filter-row class="hover:bg-white/5">
                                <td class="px-4 py-3 text-xs text-slate-300 whitespace-nowrap">{{ \App\Support\IndonesianDateTime::date($record->tanggal_absen) }}</td>
                                <td class="px-4 py-3 text-xs text-slate-300">{{ $record->siswa?->nama_siswa }}</td>
                                <td class="px-4 py-3 text-xs text-slate-300 whitespace-nowrap">{{ $record->kelas?->nama_kelas }}</td>
                                <td class="px-4 py-3 text-xs text-slate-300 whitespace-nowrap">{{ $record->jadwal?->mataPelajaran?->nama_mapel ?? '-' }}</td>
                                <td class="px-4 py-3 text-xs text-slate-300 whitespace-nowrap">
                                    @if ($record->jadwal)
                                        {{ $record->jadwal->hari }} {{ \App\Support\IndonesianDateTime::timeRange($record->jadwal->jam_mulai, $record->jadwal->jam_selesai) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-300 whitespace-nowrap">
                                    {{ $record->tahunAjaran?->nama_tahun_ajaran ?? '-' }}
                                    {{ $record->tahunAjaran?->semester ? '- '.$record->tahunAjaran->semester : '' }}
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-300 whitespace-nowrap">{{ ucfirst($record->status_kehadiran) }}</td>
                                <td class="px-4 py-3 text-xs text-slate-300">{{ $record->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center text-xs text-slate-400">Belum ada data.</td>
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
                            <p class="font-medium text-slate-100">{{ \Illuminate\Support\Str::of(\App\Support\IndonesianDateTime::date($record->tanggal_absen))->beforeLast(' ')->value() }}</p>
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
                        <div class="col-span-2">
                            <p class="text-slate-400">Mapel</p>
                            <p class="font-medium text-slate-100">{{ $record->jadwal?->mataPelajaran?->nama_mapel ?? '-' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-slate-400">Jadwal</p>
                            <p class="font-medium text-slate-100">
                                @if ($record->jadwal)
                                    {{ $record->jadwal->hari }} {{ \App\Support\IndonesianDateTime::timeRange($record->jadwal->jam_mulai, $record->jadwal->jam_selesai) }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-slate-400">Tahun Ajaran</p>
                            <p class="font-medium text-slate-100">
                                {{ $record->tahunAjaran?->nama_tahun_ajaran ?? '-' }}
                                {{ $record->tahunAjaran?->semester ? '- '.$record->tahunAjaran->semester : '' }}
                            </p>
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

    @if ($jadwalSummary->isNotEmpty())
        <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl md:p-6">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-white">Ringkasan Per Jadwal</h3>
                <p class="text-sm text-slate-400">Jumlah hadir dan ketidakhadiran dipisah per jadwal/mapel yang sedang difilter.</p>
            </div>
            <div class="space-y-3">
                @foreach ($jadwalSummary as $item)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="font-semibold text-white">{{ $item['mapel']?->nama_mapel ?? '-' }}</p>
                                <p class="text-sm text-slate-400">
                                    {{ $item['jadwal']?->hari ?? '-' }} | {{ \App\Support\IndonesianDateTime::timeRange($item['jadwal']?->jam_mulai, $item['jadwal']?->jam_selesai) }}
                                    @if ($item['guru'])
                                        | {{ $item['guru']->nama_guru }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-sm text-slate-300">
                                Total: <span class="font-semibold text-white">{{ $item['total'] }}</span>
                            </div>
                        </div>
                        <div class="mt-3 grid gap-2 text-xs sm:grid-cols-5">
                            @foreach (['hadir' => 'Hadir', 'sakit' => 'Sakit', 'izin' => 'Izin', 'alfa' => 'Alfa', 'terlambat' => 'Terlambat'] as $status => $label)
                                <div class="rounded-xl border border-white/10 bg-slate-950/40 px-3 py-2">
                                    <p class="uppercase tracking-[0.2em] text-slate-400">{{ $label }}</p>
                                    <p class="mt-1 text-base font-semibold text-white">{{ $item[$status] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function () {
        const exportButtons = document.querySelectorAll('.export-btn');

        const exportTypeMessages = {
            excel: 'Apakah Anda yakin ingin mengunduh laporan Excel?',
            pdf: 'Apakah Anda yakin ingin mengunduh laporan PDF?'
        };

        exportButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const exportType = this.getAttribute('data-export-type');
                const exportUrl = this.getAttribute('data-export-url');
                const message = exportTypeMessages[exportType] || 'Apakah Anda yakin ingin mengunduh file ini?';

                Swal.fire({
                    title: 'Konfirmasi Unduh',
                    text: message,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Unduh',
                    cancelButtonText: 'Batal',
                    reverseButtons: false,
                    didOpen: (modal) => {
                        modal.querySelector('.swal2-confirm').focus();
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = exportUrl;
                    }
                });
            });
        });
    })();
</script>
@endsection
