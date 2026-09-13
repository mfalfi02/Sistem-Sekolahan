@extends('layouts.admin')

@section('admin_title', 'Data Master')

@section('content')
<div class="space-y-8">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">User Management</p>
                <h2 class="mt-3 text-3xl font-semibold">{{ $mode === 'create' ? 'Tambah' : 'Ubah' }} User</h2>
                <p class="mt-2 text-slate-300">Buat akun login khusus untuk admin, guru, dan kepala sekolah.</p>
            </div>
            <a href="{{ route('users.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15">Kembali</a>
        </div>
    </section>

    <form method="POST" action="{{ $mode === 'create' ? route('users.store') : route('users.update', $user) }}" class="space-y-6">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        @php($selectedRole = old('role', $user->role ?? 'admin'))
        @php($guru = $user?->guru)

        <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                    @error('name')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                    @error('email')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Password</label>
                    <x-password-field
                        name="password"
                        placeholder="{{ $mode === 'edit' ? 'Kosongkan jika tidak diubah' : 'Masukkan password' }}"
                        autocomplete="new-password"
                        :show-note="true"
                        :note="$mode === 'edit' ? 'Kosongkan jika tidak diubah.' : 'Wajib diisi minimal 8 karakter.'"
                    />
                    @error('password')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Role</label>
                    <select id="user-role" name="role" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                        @foreach (['admin' => 'Admin', 'guru' => 'Guru', 'kepala_sekolah' => 'Kepala Sekolah'] as $value => $label)
                            <option value="{{ $value }}" @selected($selectedRole === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">No HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                    @error('phone')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>
                <div>
                    <input type="hidden" name="status_aktif" value="0">
                    <label class="mb-2 block text-sm font-medium text-slate-300">Status Aktif</label>
                    <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200">
                        <input type="checkbox" name="status_aktif" value="1" class="rounded border-white/20 bg-white/10 text-teal-400" @checked(old('status_aktif', $user->status_aktif ?? true))>
                        Aktif
                    </label>
                    @error('status_aktif')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <section
            data-guru-fields
            class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl {{ $selectedRole === 'guru' ? '' : 'hidden' }}"
        >
            <div class="mb-5">
                <p class="text-xs uppercase tracking-[0.24em] text-teal-200/70">Data Guru</p>
                <h3 class="mt-2 text-2xl font-semibold text-white">Detail Guru</h3>
                <p class="mt-2 text-sm text-slate-400">Field ini akan tersimpan ke tabel `guru` saat role dipilih Guru.</p>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip', $guru->nip ?? '') }}" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                    @error('nip')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">
                        <option value="">Pilih jenis kelamin</option>
                        @foreach (['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('jenis_kelamin', $guru->jenis_kelamin ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('jenis_kelamin')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Tanggal Lahir</label>
                    <input
                        type="date"
                        name="tanggal_lahir"
                        value="{{ old('tanggal_lahir', filled($guru?->tanggal_lahir) ? \Illuminate\Support\Carbon::parse($guru->tanggal_lahir)->format('Y-m-d') : '') }}"
                        class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50"
                    >
                    @error('tanggal_lahir')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-300">Alamat</label>
                    <textarea name="alamat" rows="4" class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50">{{ old('alamat', $guru->alamat ?? '') }}</textarea>
                    @error('alamat')<p class="mt-2 text-sm text-rose-300">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <div class="flex gap-3">
            <button class="rounded-2xl bg-teal-300 px-5 py-3 font-semibold text-slate-950 hover:bg-teal-200">
                {{ $mode === 'create' ? 'Simpan' : 'Perbarui' }}
            </button>
            <a href="{{ route('users.index') }}" class="rounded-2xl border border-white/10 bg-white/5 px-5 py-3 font-semibold text-slate-200 hover:bg-white/15">Batal</a>
        </div>
    </form>
</div>
<script>
    (function () {
        const roleSelect = document.getElementById('user-role');
        const guruFields = document.querySelector('[data-guru-fields]');

        if (!roleSelect || !guruFields) {
            return;
        }

        const syncVisibility = () => {
            guruFields.classList.toggle('hidden', roleSelect.value !== 'guru');
        };

        roleSelect.addEventListener('change', syncVisibility);
        syncVisibility();
    })();
</script>
@endsection
