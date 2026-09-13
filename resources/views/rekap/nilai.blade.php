@extends(in_array(auth()->user()?->role, ['admin', 'tu', 'kepala_sekolah'], true) ? 'layouts.admin' : 'layouts.app')

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
            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Fokus</p>
                    <p class="mt-2 text-lg font-semibold text-white">Hasil Akhir</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Periode</p>
                    <p class="mt-2 text-lg font-semibold text-white">Per Kelas</p>
                </div>
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
                <a href="#" data-export-type="excel" data-export-url="{{ route('rekap.nilai.export', request()->query()) }}" class="export-btn rounded-2xl bg-emerald-500 px-4 py-3 font-semibold text-white transition hover:bg-emerald-400 flex items-center justify-center gap-2 xl:flex-none">
                    Excel
                </a>
                <a href="#" data-export-type="pdf" data-export-url="{{ route('rekap.nilai.export-pdf', request()->query()) }}" class="export-btn rounded-2xl bg-red-500 px-4 py-3 font-semibold text-white transition hover:bg-red-400 flex items-center justify-center gap-2 xl:flex-none">
                    PDF
                </a>
            </div>
        </form>
    </section>

    <section class="rounded-3xl border border-teal-300/20 bg-teal-300/10 p-5 shadow-2xl">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-teal-200/70">Komposisi Nilai</p>
            <p class="mt-2 text-lg font-semibold text-white">Nilai akhir = 90% akademik + 10% absensi per mapel</p>
            <p class="mt-1 text-sm text-slate-300">
                Nilai akademik dihitung berbobot, misalnya UTS 98 dengan bobot 30% dan UAS 90 dengan bobot 40% akan dikombinasikan dulu lalu dinormalisasi berdasarkan total bobot yang tersedia.
            </p>
        </div>
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
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Absensi</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Kontribusi Absensi</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Tahun Ajaran</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Predikat</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse ($records as $index => $record)
                        @php
                            $attendanceContribution = round(((float) ($record->nilai_absensi ?? 0)) * 0.10, 2);
                        @endphp
                        <tr data-table-filter-row class="hover:bg-white/5">
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $record->siswa?->nama_siswa }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ number_format((float) $record->nilai_akhir, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ number_format((float) ($record->persentase_absensi ?? 0), 2) }}%
                                <span class="block text-xs text-slate-500">
                                    {{ (int) ($record->absensi_hadir ?? 0) }}/{{ (int) ($record->absensi_total ?? 0) }} hadir
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ number_format($attendanceContribution, 2) }}
                                <span class="block text-xs text-slate-500">10% dari skor absensi</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ $record->tahunAjaran?->nama_tahun_ajaran ?? '-' }}
                                {{ $record->tahunAjaran?->semester ? '- '.$record->tahunAjaran->semester : '' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $record->predikat ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $record->status_lulus ? 'Tuntas' : 'Belum Tuntas' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-sm text-slate-400">Belum ada nilai akhir yang dihitung.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p data-table-filter-empty hidden class="px-6 py-5 text-center text-sm text-slate-400">Tidak ada data yang cocok dengan pencarian.</p>
    </section>
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
