@extends('layouts.app')

@section('content')
<div class="grid gap-8 lg:grid-cols-2">
    <section class="rounded-3xl border border-white/10 bg-white/8 p-8 shadow-2xl backdrop-blur-xl">
        <span class="inline-flex rounded-full border border-teal-400/30 bg-teal-400/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-teal-200">
            Login Sistem
        </span>
        <h2 class="mt-5 text-4xl font-semibold leading-tight">Masuk ke dashboard guru, siswa, dan admin.</h2>
        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300">
            Sistem ini disiapkan untuk mengelola absensi, penilaian, dan laporan sekolah secara terpusat.
        </p>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <p class="text-sm text-slate-400">Guru</p>
                <p class="mt-2 text-lg font-semibold">Input absensi & nilai</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <p class="text-sm text-slate-400">Siswa</p>
                <p class="mt-2 text-lg font-semibold">Lihat hasil belajar</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <p class="text-sm text-slate-400">Admin</p>
                <p class="mt-2 text-lg font-semibold">Kelola data & laporan</p>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl backdrop-blur-xl">
        <h3 class="text-2xl font-semibold">Masuk</h3>
        <p class="mt-2 text-sm text-slate-400">Gunakan akun yang sudah didaftarkan oleh admin.</p>

        @php($errorBag = session('errors'))
        @if ($errorBag && $errorBag->any())
            <div class="mt-6 rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 text-sm text-rose-200">
                {{ $errorBag->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-5">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none ring-0 placeholder:text-slate-500 focus:border-teal-300/50">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-300">Password</label>
                <input type="password" name="password" required
                    class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none ring-0 placeholder:text-slate-500 focus:border-teal-300/50">
            </div>

            <label class="flex items-center gap-3 text-sm text-slate-300">
                <input type="checkbox" name="remember" value="1" class="rounded border-white/20 bg-white/10 text-teal-400">
                Ingat saya
            </label>

            <button class="w-full rounded-2xl bg-teal-300 px-4 py-3 font-semibold text-slate-950 transition hover:bg-teal-200">
                Masuk ke Sistem
            </button>
        </form>
    </section>
</div>
@endsection
