@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">User Management</p>
                <h2 class="mt-3 text-3xl font-semibold">Kelola User</h2>
                <p class="mt-2 text-slate-300">Admin mengatur akun login untuk guru, siswa, dan admin.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15">Kembali</a>
                <a href="{{ route('users.create') }}" class="rounded-xl bg-teal-300 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-teal-200">Tambah User</a>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @error('user')
        <div class="rounded-2xl border border-rose-500/20 bg-rose-500/10 px-4 py-3 text-sm text-rose-200">
            {{ $message }}
        </div>
    @enderror

    <section class="overflow-hidden rounded-3xl border border-white/10 bg-slate-900/60 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-left">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Nama</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Email</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Role</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Kelas</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Status</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse ($users as $user)
                        <tr class="hover:bg-white/5">
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ strtoupper($user->role) }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $user->role === 'siswa' ? ($user->siswa?->kelas?->nama_kelas ?? '-') : '-' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-300">{{ $user->status_aktif ? 'Aktif' : 'Nonaktif' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-slate-200 hover:bg-white/15">Edit</a>
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
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
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">Belum ada user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div>{{ $users->links() }}</div>
</div>
@endsection
