<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));

        $query = User::whereIn('role', ['admin', 'guru', 'kepala_sekolah'])->with('siswa.kelas');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return view('users.index', [
            'users' => $query->latest()->paginate(10),
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('users.form', [
            'user' => null,
            'kelasList' => Kelas::orderBy('nama_kelas')->get(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);

        DB::transaction(function () use ($data): void {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $data['role'],
                'phone' => $data['phone'] ?? null,
                'status_aktif' => $data['status_aktif'] ?? false,
            ]);

            $this->syncGuruProfile($user, $data);
            $this->syncSiswaProfile($user, $data);
        });

        ActivityLogger::record(
            $request->user(),
            'user_create',
            'Tambah user '.$data['name'],
            'Role '.strtoupper($data['role']).' berhasil dibuat.',
            route('users.index')
        );

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $user->loadMissing('siswa.kelas', 'guru');

        return view('users.form', [
            'user' => $user,
            'kelasList' => Kelas::orderBy('nama_kelas')->get(),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validateRequest($request, $user);

        DB::transaction(function () use ($data, $user): void {
            $payload = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'phone' => $data['phone'] ?? null,
                'status_aktif' => $data['status_aktif'] ?? false,
            ];

            if (filled($data['password'] ?? null)) {
                $payload['password'] = $data['password'];
            }

            $user->update($payload);

            $freshUser = $user->fresh();
            $this->syncGuruProfile($freshUser, $data);
            $this->syncSiswaProfile($freshUser, $data);
        });

        ActivityLogger::record(
            $request->user(),
            'user_update',
            'Perbarui user '.$data['name'],
            'Role '.strtoupper($data['role']).' berhasil diperbarui.',
            route('users.index')
        );

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'Anda tidak bisa menghapus akun sendiri.']);
        }

        DB::transaction(function () use ($user): void {
            $user->guru?->delete();
            $user->siswa?->delete();
            $user->delete();
        });

        ActivityLogger::record(
            $request->user(),
            'user_delete',
            'Hapus user '.$user->name,
            'Akun '.strtoupper($user->role).' dihapus dari sistem.',
            route('users.index')
        );

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateRequest(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                $user ? Rule::unique('users', 'email')->ignore($user->id) : Rule::unique('users', 'email'),
            ],
            'password' => [$user ? 'nullable' : 'required', 'string', Password::min(8)],
            'role' => ['required', 'in:admin,guru,kepala_sekolah'],
            'phone' => ['nullable', 'string', 'max:20'],
            'status_aktif' => ['nullable', 'boolean'],
            'nip' => ['required_if:role,guru', 'nullable', 'string', 'max:30'],
            'jenis_kelamin' => ['required_if:role,guru', 'nullable', 'in:Laki-laki,Perempuan'],
            'tanggal_lahir' => ['required_if:role,guru', 'nullable', 'date'],
            'alamat' => ['required_if:role,guru', 'nullable', 'string'],
        ]);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        return $data;
    }

    private function syncSiswaProfile(User $user, array $data): void
    {
        if ($user->role !== 'siswa') {
            return;
        }

        Siswa::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nis' => $user->siswa?->nis ?? 'SISWA-'.$user->id,
                'nisn' => $user->siswa?->nisn,
                'nama_siswa' => $data['name'],
                'jenis_kelamin' => $user->siswa?->jenis_kelamin,
                'tanggal_lahir' => $user->siswa?->tanggal_lahir,
                'alamat' => $user->siswa?->alamat,
                'no_hp' => $data['phone'] ?? $user->siswa?->no_hp,
                'foto' => $user->siswa?->foto,
                'kelas_id' => $data['kelas_id'] ?? $user->siswa?->kelas_id,
            ]
        );
    }

    private function syncGuruProfile(User $user, array $data): void
    {
        if ($user->role !== 'guru') {
            $user->guru?->delete();

            return;
        }

        Guru::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nip' => $data['nip'] ?? $user->guru?->nip,
                'nama_guru' => $data['name'],
                'jenis_kelamin' => $data['jenis_kelamin'] ?? $user->guru?->jenis_kelamin,
                'tanggal_lahir' => $data['tanggal_lahir'] ?? $user->guru?->tanggal_lahir,
                'alamat' => $data['alamat'] ?? $user->guru?->alamat,
                'no_hp' => $data['phone'] ?? $user->guru?->no_hp,
                'foto' => $user->guru?->foto,
            ]
        );
    }
}
