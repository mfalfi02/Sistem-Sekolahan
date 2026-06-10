@extends(in_array(auth()->user()?->role, ['admin', 'tu'], true) ? 'layouts.admin' : 'layouts.app')

@section('admin_title', 'Semua Aktivitas')

@section('content')
<div class="space-y-5 md:space-y-6">
    <section class="overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 shadow-2xl">
        <div class="grid gap-6 px-5 py-6 md:px-8 md:py-8 xl:grid-cols-[1.2fr_0.8fr] xl:items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.32em] text-teal-200/70">Aktivitas Sistem</p>
                <h1 class="mt-3 text-2xl font-semibold text-white md:text-4xl">Semua Aktivitas</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300 md:text-base">
                    Lihat seluruh aktivitas yang tercatat di sistem, lengkap dengan tanggal, waktu, pelaku, dan keterangan. Gunakan filter kalender untuk mempersempit hasil.
                </p>
            </div>
            <div class="grid gap-3 sm:grid-cols-3 xl:grid-cols-1">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total Aktivitas</p>
                    <p class="mt-2 text-2xl font-semibold text-white">{{ number_format((int) $totalActivities) }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Filter</p>
                    <p class="mt-2 text-lg font-semibold text-white">Kalender Tanggal</p>
                </div>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-4 text-sm font-medium text-white transition hover:bg-white/10">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-4 shadow-2xl md:p-6">
        <form method="GET" action="{{ route('activities.index') }}" class="grid gap-4 xl:grid-cols-[1fr_1fr_auto]">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal Dari</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-teal-300/50">
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal Sampai</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-teal-300/50">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full rounded-2xl bg-teal-300 px-5 py-3 font-semibold text-slate-950 transition hover:bg-teal-200">
                    Filter Kalender
                </button>
            </div>
        </form>
    </section>

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
        <div class="border-b border-white/10 px-5 py-4 md:px-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-white">Daftar Aktivitas</h2>
                    <p class="text-sm text-slate-400">Urutan terbaru ditampilkan di atas.</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300">
                    Tampil {{ $activities->count() }} data per halaman
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-left">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Tanggal</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Waktu</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Aktivitas</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Oleh</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Keterangan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse ($activities as $activity)
                        <tr class="hover:bg-white/5">
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-300">
                                {{ $activity->created_at?->format('d M Y') ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-300">
                                {{ $activity->created_at?->format('H:i') ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-white">{{ $activity->title }}</p>
                                <p class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-400">{{ $activity->activity_type }}</p>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-300">
                                {{ $activity->user?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ $activity->detail ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if ($activity->link)
                                    <a href="{{ $activity->link }}" class="inline-flex rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                                        Buka
                                    </a>
                                @else
                                    <span class="text-sm text-slate-500">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">
                                Belum ada aktivitas yang tercatat pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-white/10 px-5 py-4 md:px-6">
            {{ $activities->links() }}
        </div>
    </section>
</div>
@endsection
