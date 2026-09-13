@extends(in_array(auth()->user()?->role, ['admin', 'tu', 'kepala_sekolah'], true) ? 'layouts.admin' : 'layouts.app')

@section('admin_title', ($schema['title'] ?? 'Data Master'))

@section('content')
<div data-table-filter class="space-y-8">
    @php($canManage = in_array(auth()->user()?->role, ['admin', 'tu'], true))
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Master Data</p>
                <h2 class="mt-3 text-3xl font-semibold">{{ $schema['title'] }}</h2>
                <p class="mt-2 text-slate-300">Kelola data {{ strtolower($schema['title']) }} yang digunakan oleh absensi dan penilaian.</p>
                @if ($type === 'tahun-ajaran' && $activeTahunAjaran)
                    <p class="mt-3 text-sm text-slate-300">
                        Tahun ajaran aktif saat ini:
                        <span class="font-semibold text-white">{{ $activeTahunAjaran->nama_tahun_ajaran }} {{ $activeTahunAjaran->semester }}</span>
                    </p>
                @endif
            </div>
            <div class="flex flex-wrap gap-3">
                @if ($type === 'jadwal' && $canManage)
                    <a href="{{ route('masters.export-pdf', array_merge(['type' => $type], request()->query())) }}" class="rounded-xl border border-rose-500/20 bg-rose-500/10 px-4 py-2 text-sm font-semibold text-rose-100 hover:bg-rose-500/20">
                        Cetak PDF
                    </a>
                @endif
                @if ($canManage && $type === 'tahun-ajaran')
                    <a href="{{ route('masters.create', $type) }}" class="rounded-xl bg-teal-300 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-teal-200">
                        Buat Tahun Ajaran Baru
                    </a>
                @elseif ($canManage && $type !== 'tahun-ajaran')
                    <a href="{{ route('masters.create', $type) }}" class="rounded-xl bg-teal-300 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-teal-200">
                        Tambah Data
                    </a>
                @endif
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Bar -->
    <section class="rounded-2xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl">
        <form method="GET" action="{{ route('masters.index', $type) }}" class="grid gap-3 {{ in_array($type, ['siswa', 'jadwal'], true) ? 'lg:grid-cols-3' : 'lg:grid-cols-2' }}">
            <input
                type="text"
                name="search"
                data-table-filter-input
                value="{{ $search }}"
                placeholder="Cari data..."
                class="flex-1 rounded-lg border border-white/20 bg-white/5 px-4 py-2 text-sm text-white placeholder-slate-400 focus:border-teal-500 focus:outline-none transition"
            >
            @if (in_array($type, ['siswa', 'jadwal'], true))
                <select
                    name="kelas_id"
                    class="rounded-lg border border-white/20 bg-white/5 px-4 py-2 text-sm text-white placeholder-slate-400 focus:border-teal-500 focus:outline-none transition"
                >
                    <option value="">Semua Kelas</option>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected(optional($selectedKelas)->id === $kelas->id)>{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            @endif
            <button type="submit" class="rounded-lg bg-teal-300 px-6 py-2 text-sm font-semibold text-slate-950 hover:bg-teal-200 transition">
                Cari
            </button>
            @if ($search || (in_array($type, ['siswa', 'jadwal'], true) && request()->filled('kelas_id')))
                <a href="{{ route('masters.index', $type) }}" class="rounded-lg border border-white/20 bg-white/5 px-6 py-2 text-sm font-semibold text-slate-200 hover:bg-white/10 transition">
                    Reset
                </a>
            @endif
        </form>
    </section>

    <section class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-left">
                <thead class="bg-white/5">
                    <tr>
                        @foreach ($schema['columns'] as $column)
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">{{ $column['label'] }}</th>
                        @endforeach
                        @if ($canManage)
                            <th class="px-6 py-4 text-sm font-semibold text-slate-200">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse ($records as $record)
                        <tr data-table-filter-row class="hover:bg-white/5">
                            @foreach ($schema['columns'] as $column)
                                <td class="px-6 py-4 text-sm text-slate-300">
                                    @php($value = data_get($record, $column['field']))
                                    @if ($column['field'] === 'jam_mulai')
                                        {{ \App\Support\IndonesianDateTime::timeRange($value, data_get($record, 'jam_selesai')) }}
                                    @elseif (is_bool($value))
                                        {{ $value ? 'Aktif' : 'Nonaktif' }}
                                    @elseif ($value instanceof \Illuminate\Support\Carbon)
                                        {{ \App\Support\IndonesianDateTime::date($value) }}
                                    @elseif ($value instanceof \Illuminate\Support\CarbonImmutable)
                                        {{ \App\Support\IndonesianDateTime::date($value) }}
                                    @elseif ($column['field'] === 'status_aktif')
                                        {{ $value ? 'Aktif' : 'Nonaktif' }}
                                    @else
                                        {{ $value ?? '-' }}
                                    @endif
                                </td>
                            @endforeach
                            @if ($canManage)
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('masters.edit', [$type, $record->id]) }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-slate-200 hover:bg-white/15">
                                            Edit
                                        </a>
                                        @if ($type === 'tahun-ajaran')
                                            @if (! $record->status_aktif)
                                                <form method="POST" action="{{ route('masters.activate', [$type, $record->id]) }}">
                                                    @csrf
                                                    <button class="rounded-xl border border-teal-400/20 bg-teal-400/10 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-teal-100 hover:bg-teal-400/20">
                                                        Aktifkan
                                                    </button>
                                                </form>
                                            @else
                                                <span class="rounded-xl border border-emerald-400/20 bg-emerald-400/10 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-emerald-100">
                                                    Aktif
                                                </span>
                                            @endif
                                        @endif
                                        <form method="POST" action="{{ route('masters.destroy', [$type, $record->id]) }}" data-confirm="Hapus data ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-xl border border-rose-500/20 bg-rose-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-rose-200 hover:bg-rose-500/20">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($schema['columns']) + ($canManage ? 1 : 0) }}" class="px-6 py-10 text-center text-sm text-slate-400">
                                Belum ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p data-table-filter-empty hidden class="px-6 py-5 text-center text-sm text-slate-400">Tidak ada data yang cocok dengan pencarian.</p>
    </section>

    <div>
        {{ $records->links() }}
    </div>
</div>
@endsection
