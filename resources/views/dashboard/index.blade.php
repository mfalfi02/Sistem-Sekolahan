@extends(in_array($role, ['admin', 'tu', 'kepala_sekolah'], true) ? 'layouts.admin' : 'layouts.app')

@section('admin_title', $role === 'kepala_sekolah' ? 'Dashboard Kepala Sekolah' : (in_array($role, ['admin', 'tu'], true) ? 'Dashboard Admin' : 'Dashboard'))

@section('content')
<div class="space-y-8">
    @if (in_array($role, ['admin', 'tu'], true))
        @include('dashboard.admin')
    @elseif ($role === 'kepala_sekolah')
        @include('dashboard.kepala-sekolah')
    @elseif ($role === 'guru')
        @include('dashboard.guru')
    @elseif ($role === 'siswa')
        @include('dashboard.siswa')
    @else
        <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
            <p class="text-sm uppercase tracking-[0.3em] text-teal-200/70">Dashboard</p>
            <h2 class="text-3xl font-semibold">Selamat datang, {{ $user?->name ?? 'Pengguna' }}</h2>
            <p class="mt-3 max-w-3xl text-slate-300">
                Pilih role Anda untuk melihat dashboard yang sesuai.
            </p>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            @foreach ($cards as $card)
                <div class="rounded-3xl border border-white/10 bg-slate-900/60 p-6">
                    <div class="text-3xl mb-2">{{ $card['icon'] ?? '' }}</div>
                    <p class="text-sm text-slate-400">{{ $card['label'] }}</p>
                    <p class="mt-2 text-3xl font-semibold">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </section>
    @endif
</div>
@endsection
