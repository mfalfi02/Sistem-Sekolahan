@extends('layouts.admin')

@section('admin_title', ($schema['title'] ?? 'Data Master'))

@section('content')
<div data-table-filter class="space-y-8">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Master Data</p>
                <h2 class="mt-3 text-3xl font-semibold">{{ $schema['title'] }}</h2>
                <p class="mt-2 text-slate-300">Kelola data {{ strtolower($schema['title']) }} yang digunakan oleh absensi dan penilaian.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15">
                    Kembali
                </a>
                <a href="{{ route('masters.create', $type) }}" class="rounded-xl bg-teal-300 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-teal-200">
                    Tambah Data
                </a>
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
        <form method="GET" action="{{ route('masters.index', $type) }}" class="flex gap-3">
            <input
                type="text"
                name="search"
                data-table-filter-input
                value="{{ $search }}"
                placeholder="Cari data..."
                class="flex-1 rounded-lg border border-white/20 bg-white/5 px-4 py-2 text-sm text-white placeholder-slate-400 focus:border-teal-500 focus:outline-none transition"
            >
            <button type="submit" class="rounded-lg bg-teal-300 px-6 py-2 text-sm font-semibold text-slate-950 hover:bg-teal-200 transition">
                Cari
            </button>
            @if ($search)
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
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse ($records as $record)
                        <tr data-table-filter-row class="hover:bg-white/5">
                            @foreach ($schema['columns'] as $column)
                                <td class="px-6 py-4 text-sm text-slate-300">
                                    @php($value = data_get($record, $column['field']))
                                    @if ($column['field'] === 'jam_mulai')
                                        {{ trim(($value ? substr((string) $value, 0, 5) : '-') . ' - ' . (data_get($record, 'jam_selesai') ? substr((string) data_get($record, 'jam_selesai'), 0, 5) : '-')) }}
                                    @elseif (is_bool($value))
                                        {{ $value ? 'Aktif' : 'Nonaktif' }}
                                    @elseif ($value instanceof \Illuminate\Support\Carbon)
                                        {{ $value->format('d M Y') }}
                                    @elseif ($value instanceof \Illuminate\Support\CarbonImmutable)
                                        {{ $value->format('d M Y') }}
                                    @elseif ($column['field'] === 'status_aktif')
                                        {{ $value ? 'Aktif' : 'Nonaktif' }}
                                    @else
                                        {{ $value ?? '-' }}
                                    @endif
                                </td>
                            @endforeach
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('masters.edit', [$type, $record->id]) }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-slate-200 hover:bg-white/15">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('masters.destroy', [$type, $record->id]) }}" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-xl border border-rose-500/20 bg-rose-500/10 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-rose-200 hover:bg-rose-500/20">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($schema['columns']) + 1 }}" class="px-6 py-10 text-center text-sm text-slate-400">
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
        {{ $records->appends(['search' => $search])->links() }}
    </div>
</div>
@endsection
