<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    /**
     * @return array<string, array<string, mixed>>
     */
    private function schemas(): array
    {
        return [
            'tahun-ajaran' => [
                'title' => 'Tahun Ajaran',
                'model' => TahunAjaran::class,
                'relations' => [],
                'columns' => [
                    ['label' => 'Tahun Ajaran', 'field' => 'nama_tahun_ajaran'],
                    ['label' => 'Semester', 'field' => 'semester'],
                    ['label' => 'Mulai', 'field' => 'tanggal_mulai'],
                    ['label' => 'Selesai', 'field' => 'tanggal_selesai'],
                    ['label' => 'Aktif', 'field' => 'status_aktif'],
                ],
                'sections' => [
                    [
                        'title' => 'Data Tahun Ajaran',
                        'fields' => [
                            ['name' => 'nama_tahun_ajaran', 'label' => 'Nama Tahun Ajaran', 'type' => 'text', 'required' => true],
                            ['name' => 'semester', 'label' => 'Semester', 'type' => 'select', 'required' => true, 'options' => [
                                'Ganjil' => 'Ganjil',
                                'Genap' => 'Genap',
                            ]],
                            ['name' => 'tanggal_mulai', 'label' => 'Tanggal Mulai', 'type' => 'date', 'required' => false],
                            ['name' => 'tanggal_selesai', 'label' => 'Tanggal Selesai', 'type' => 'date', 'required' => false],
                            ['name' => 'status_aktif', 'label' => 'Status Aktif', 'type' => 'checkbox', 'required' => false],
                        ],
                    ],
                ],
            ],
            'guru' => [
                'title' => 'Guru',
                'model' => Guru::class,
                'relations' => ['user'],
                'columns' => [
                    ['label' => 'Nama Akun', 'field' => 'user.name'],
                    ['label' => 'Email', 'field' => 'user.email'],
                    ['label' => 'NIP', 'field' => 'nip'],
                    ['label' => 'Nama Guru', 'field' => 'nama_guru'],
                    ['label' => 'No HP', 'field' => 'no_hp'],
                ],
                'sections' => [
                    [
                        'title' => 'Akun Login',
                        'fields' => [
                            ['name' => 'name', 'label' => 'Nama Akun', 'type' => 'text', 'required' => true],
                            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                            ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => false, 'hint' => 'Kosongkan saat edit jika password tidak diganti.'],
                            ['name' => 'phone', 'label' => 'No HP Akun', 'type' => 'text', 'required' => false],
                            ['name' => 'status_aktif', 'label' => 'Status Aktif', 'type' => 'checkbox', 'required' => false],
                        ],
                    ],
                    [
                        'title' => 'Data Guru',
                        'fields' => [
                            ['name' => 'nip', 'label' => 'NIP', 'type' => 'text', 'required' => false],
                            ['name' => 'nama_guru', 'label' => 'Nama Guru', 'type' => 'text', 'required' => true],
                            ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'required' => false, 'options' => [
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ]],
                            ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date', 'required' => false],
                            ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'required' => false],
                            ['name' => 'no_hp', 'label' => 'No HP', 'type' => 'text', 'required' => false],
                        ],
                    ],
                ],
            ],
            'siswa' => [
                'title' => 'Siswa',
                'model' => Siswa::class,
                'relations' => ['user', 'kelas'],
                'columns' => [
                    ['label' => 'Nama Akun', 'field' => 'user.name'],
                    ['label' => 'Email', 'field' => 'user.email'],
                    ['label' => 'NIS', 'field' => 'nis'],
                    ['label' => 'Nama Siswa', 'field' => 'nama_siswa'],
                    ['label' => 'Kelas', 'field' => 'kelas.nama_kelas'],
                ],
                'sections' => [
                    [
                        'title' => 'Akun Login',
                        'fields' => [
                            ['name' => 'name', 'label' => 'Nama Akun', 'type' => 'text', 'required' => true],
                            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                            ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => false, 'hint' => 'Kosongkan saat edit jika password tidak diganti.'],
                            ['name' => 'phone', 'label' => 'No HP Akun', 'type' => 'text', 'required' => false],
                            ['name' => 'status_aktif', 'label' => 'Status Aktif', 'type' => 'checkbox', 'required' => false],
                        ],
                    ],
                    [
                        'title' => 'Data Siswa',
                        'fields' => [
                            ['name' => 'nis', 'label' => 'NIS', 'type' => 'text', 'required' => true],
                            ['name' => 'nisn', 'label' => 'NISN', 'type' => 'text', 'required' => false],
                            ['name' => 'nama_siswa', 'label' => 'Nama Siswa', 'type' => 'text', 'required' => true],
                            ['name' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'type' => 'select', 'required' => false, 'options' => [
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ]],
                            ['name' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'type' => 'date', 'required' => false],
                            ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'required' => false],
                            ['name' => 'no_hp', 'label' => 'No HP', 'type' => 'text', 'required' => false],
                            ['name' => 'kelas_id', 'label' => 'Kelas', 'type' => 'select', 'required' => false, 'options_source' => 'kelas'],
                        ],
                    ],
                ],
            ],
            'kelas' => [
                'title' => 'Kelas',
                'model' => Kelas::class,
                'relations' => ['waliGuru', 'tahunAjaran'],
                'columns' => [
                    ['label' => 'Nama Kelas', 'field' => 'nama_kelas'],
                    ['label' => 'Tingkat', 'field' => 'tingkat'],
                    ['label' => 'Jurusan', 'field' => 'jurusan'],
                    ['label' => 'Wali Kelas', 'field' => 'waliGuru.nama_guru'],
                    ['label' => 'Tahun Ajaran', 'field' => 'tahunAjaran.nama_tahun_ajaran'],
                ],
                'sections' => [
                    [
                        'title' => 'Data Kelas',
                        'fields' => [
                            ['name' => 'nama_kelas', 'label' => 'Nama Kelas', 'type' => 'text', 'required' => true],
                            ['name' => 'tingkat', 'label' => 'Tingkat', 'type' => 'text', 'required' => false],
                            ['name' => 'jurusan', 'label' => 'Jurusan', 'type' => 'text', 'required' => false],
                            ['name' => 'kapasitas', 'label' => 'Kapasitas', 'type' => 'number', 'required' => false],
                            ['name' => 'wali_guru_id', 'label' => 'Wali Kelas', 'type' => 'select', 'required' => false, 'options_source' => 'guru'],
                            ['name' => 'tahun_ajaran_id', 'label' => 'Tahun Ajaran', 'type' => 'select', 'required' => false, 'options_source' => 'tahun_ajaran'],
                        ],
                    ],
                ],
            ],
            'mata-pelajaran' => [
                'title' => 'Mata Pelajaran',
                'model' => MataPelajaran::class,
                'relations' => [],
                'columns' => [
                    ['label' => 'Kode', 'field' => 'kode_mapel'],
                    ['label' => 'Mata Pelajaran', 'field' => 'nama_mapel'],
                    ['label' => 'Kelompok', 'field' => 'kelompok_mapel'],
                    ['label' => 'Jam/Minggu', 'field' => 'jam_mingguan'],
                    ['label' => 'KKM', 'field' => 'kkm'],
                ],
                'sections' => [
                    [
                        'title' => 'Data Mata Pelajaran',
                        'fields' => [
                            ['name' => 'kode_mapel', 'label' => 'Kode Mapel', 'type' => 'text', 'required' => false],
                            ['name' => 'nama_mapel', 'label' => 'Nama Mapel', 'type' => 'text', 'required' => true],
                            ['name' => 'kelompok_mapel', 'label' => 'Kelompok Mapel', 'type' => 'text', 'required' => false],
                            ['name' => 'jam_mingguan', 'label' => 'Jam per Minggu', 'type' => 'number', 'required' => false],
                            ['name' => 'kkm', 'label' => 'KKM', 'type' => 'number', 'required' => false],
                        ],
                    ],
                ],
            ],
            'jadwal' => [
                'title' => 'Jadwal',
                'model' => Jadwal::class,
                'relations' => ['kelas', 'guru.user', 'mataPelajaran', 'tahunAjaran'],
                'columns' => [
                    ['label' => 'Kelas', 'field' => 'kelas.nama_kelas'],
                    ['label' => 'Guru', 'field' => 'guru.nama_guru'],
                    ['label' => 'Mata Pelajaran', 'field' => 'mataPelajaran.nama_mapel'],
                    ['label' => 'Hari', 'field' => 'hari'],
                    ['label' => 'Jam', 'field' => 'jam_mulai'],
                    ['label' => 'Ruang', 'field' => 'ruang'],
                    ['label' => 'Aktif', 'field' => 'status_aktif'],
                ],
                'sections' => [
                    [
                        'title' => 'Data Jadwal',
                        'fields' => [
                            ['name' => 'kelas_id', 'label' => 'Kelas', 'type' => 'select', 'required' => true, 'options_source' => 'kelas'],
                            ['name' => 'guru_id', 'label' => 'Guru', 'type' => 'select', 'required' => true, 'options_source' => 'guru'],
                            ['name' => 'mata_pelajaran_id', 'label' => 'Mata Pelajaran', 'type' => 'select', 'required' => true, 'options_source' => 'mata_pelajaran'],
                            ['name' => 'tahun_ajaran_id', 'label' => 'Tahun Ajaran', 'type' => 'select', 'required' => true, 'options_source' => 'tahun_ajaran'],
                            ['name' => 'hari', 'label' => 'Hari', 'type' => 'select', 'required' => true, 'options' => [
                                'Senin' => 'Senin',
                                'Selasa' => 'Selasa',
                                'Rabu' => 'Rabu',
                                'Kamis' => 'Kamis',
                                'Jumat' => 'Jumat',
                                'Sabtu' => 'Sabtu',
                                'Minggu' => 'Minggu',
                            ]],
                            ['name' => 'jam_mulai', 'label' => 'Jam Mulai', 'type' => 'time', 'required' => true],
                            ['name' => 'jam_selesai', 'label' => 'Jam Selesai', 'type' => 'time', 'required' => true],
                            ['name' => 'ruang', 'label' => 'Ruang', 'type' => 'text', 'required' => false],
                            ['name' => 'status_aktif', 'label' => 'Status Aktif', 'type' => 'checkbox', 'required' => false],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function schema(string $type): array
    {
        $schemas = $this->schemas();

        abort_unless(isset($schemas[$type]), 404);

        return $schemas[$type];
    }

    private function modelFor(string $type): string
    {
        return $this->schema($type)['model'];
    }

    private function recordTitle(string $type): string
    {
        return $this->schema($type)['title'];
    }

    private function optionsForField(string $source): array
    {
        return match ($source) {
            'kelas' => Kelas::query()->orderBy('nama_kelas')->pluck('nama_kelas', 'id')->all(),
            'guru' => Guru::query()->orderBy('nama_guru')->pluck('nama_guru', 'id')->all(),
            'mata_pelajaran' => MataPelajaran::query()->orderBy('nama_mapel')->pluck('nama_mapel', 'id')->all(),
            'tahun_ajaran' => TahunAjaran::query()->orderByDesc('id')->get()->mapWithKeys(fn ($item) => [
                $item->id => trim($item->nama_tahun_ajaran.' '.$item->semester),
            ])->all(),
            default => [],
        };
    }

    public function index(Request $request, string $type): View
    {
        $schema = $this->schema($type);
        $model = $this->modelFor($type);
        $query = $model::query();
        $search = trim((string) $request->input('search', ''));

        if (! empty($schema['relations'])) {
            $query->with($schema['relations']);
        }

        // Add search functionality
        if ($search) {
            $query->where(function ($q) use ($search, $schema) {
                $searchFields = $schema['searchFields'] ?? [];

                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }

                // If no specific search fields defined, search on first text column
                if (empty($searchFields)) {
                    $q->orWhere($schema['columns'][0]['field'] ?? 'id', 'like', "%{$search}%");
                }
            });
        }

        return view('masters.index', [
            'schema' => $schema,
            'type' => $type,
            'records' => $query->latest()->paginate(10)->withQueryString(),
            'search' => $search,
        ]);
    }

    public function create(string $type): View
    {
        $schema = $this->schema($type);
        $options = $this->sectionOptions($schema);

        return view('masters.form', [
            'schema' => $schema,
            'type' => $type,
            'record' => null,
            'options' => $options,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        $schema = $this->schema($type);
        $validated = $this->validateRequest($request, $type, null);

        $this->persist($type, $validated);

        ActivityLogger::record(
            $request->user(),
            'master_create',
            'Tambah '.$schema['title'],
            'Data '.$schema['title'].' berhasil ditambahkan.',
            route('masters.index', $type)
        );

        return redirect()
            ->route('masters.index', $type)
            ->with('success', $schema['title'].' berhasil ditambahkan.');
    }

    public function edit(string $type, int $id): View
    {
        $schema = $this->schema($type);
        $record = $this->modelFor($type)::query()->findOrFail($id);
        $options = $this->sectionOptions($schema);

        return view('masters.form', [
            'schema' => $schema,
            'type' => $type,
            'record' => $record,
            'options' => $options,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, string $type, int $id): RedirectResponse
    {
        $schema = $this->schema($type);
        $record = $this->modelFor($type)::query()->findOrFail($id);
        $validated = $this->validateRequest($request, $type, $record);

        $this->persist($type, $validated, $record);

        ActivityLogger::record(
            $request->user(),
            'master_update',
            'Perbarui '.$schema['title'],
            'Data '.$schema['title'].' berhasil diperbarui.',
            route('masters.index', $type)
        );

        return redirect()
            ->route('masters.index', $type)
            ->with('success', $schema['title'].' berhasil diperbarui.');
    }

    public function destroy(string $type, int $id): RedirectResponse
    {
        $schema = $this->schema($type);
        $record = $this->modelFor($type)::query()->findOrFail($id);

        DB::transaction(function () use ($type, $record): void {
            if (in_array($type, ['guru', 'siswa'], true)) {
                $record->user?->delete();
            }

            $record->delete();
        });

        ActivityLogger::record(
            $request->user(),
            'master_delete',
            'Hapus '.$schema['title'],
            'Data '.$schema['title'].' berhasil dihapus.',
            route('masters.index', $type)
        );

        return redirect()
            ->route('masters.index', $type)
            ->with('success', $schema['title'].' berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function sectionOptions(array $schema): array
    {
        $options = [];

        foreach ($schema['sections'] as $section) {
            foreach ($section['fields'] as $field) {
                if (($field['type'] ?? null) === 'select' && isset($field['options_source'])) {
                    $options[$field['name']] = $this->optionsForField($field['options_source']);
                }

                if (($field['type'] ?? null) === 'select' && isset($field['options'])) {
                    $options[$field['name']] = $field['options'];
                }
            }
        }

        return $options;
    }

    /**
     * @return array<string, mixed>
     */
    private function validateRequest(Request $request, string $type, mixed $record = null): array
    {
        return match ($type) {
            'tahun-ajaran' => $request->validate([
                'nama_tahun_ajaran' => ['required', 'string', 'max:20'],
                'semester' => ['required', 'string', 'max:20'],
                'tanggal_mulai' => ['nullable', 'date'],
                'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
                'status_aktif' => ['nullable', 'boolean'],
            ]),
            'mata-pelajaran' => $request->validate([
                'kode_mapel' => array_values(array_filter([
                    'nullable',
                    'string',
                    'max:20',
                    $record ? Rule::unique('mata_pelajaran', 'kode_mapel')->ignore($record->id) : Rule::unique('mata_pelajaran', 'kode_mapel'),
                ])),
                'nama_mapel' => ['required', 'string', 'max:255'],
                'kelompok_mapel' => ['nullable', 'string', 'max:50'],
                'jam_mingguan' => ['nullable', 'integer', 'min:0'],
                'kkm' => ['nullable', 'integer', 'min:0'],
            ]),
            'jadwal' => $request->validate([
                'kelas_id' => ['required', 'exists:kelas,id'],
                'guru_id' => ['required', 'exists:guru,id'],
                'mata_pelajaran_id' => ['required', 'exists:mata_pelajaran,id'],
                'tahun_ajaran_id' => ['required', 'exists:tahun_ajaran,id'],
                'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
                'jam_mulai' => ['required', 'date_format:H:i'],
                'jam_selesai' => ['required', 'date_format:H:i'],
                'ruang' => ['nullable', 'string', 'max:50'],
                'status_aktif' => ['nullable', 'boolean'],
            ]),
            'kelas' => $request->validate([
                'nama_kelas' => ['required', 'string', 'max:50'],
                'tingkat' => ['nullable', 'string', 'max:20'],
                'jurusan' => ['nullable', 'string', 'max:50'],
                'kapasitas' => ['nullable', 'integer', 'min:0'],
                'wali_guru_id' => ['nullable', 'exists:guru,id'],
                'tahun_ajaran_id' => ['nullable', 'exists:tahun_ajaran,id'],
            ]),
            'guru' => $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => array_values(array_filter([
                    'required',
                    'email',
                    'max:255',
                    $record?->user_id
                        ? Rule::unique('users', 'email')->ignore($record->user_id)
                        : Rule::unique('users', 'email'),
                ])),
                'password' => [$record ? 'nullable' : 'required', 'string', Password::min(8)],
                'phone' => ['nullable', 'string', 'max:20'],
                'status_aktif' => ['nullable', 'boolean'],
                'nip' => array_values(array_filter([
                    'nullable',
                    'string',
                    'max:30',
                    $record ? Rule::unique('guru', 'nip')->ignore($record->id) : Rule::unique('guru', 'nip'),
                ])),
                'nama_guru' => ['required', 'string', 'max:255'],
                'jenis_kelamin' => ['nullable', 'in:Laki-laki,Perempuan'],
                'tanggal_lahir' => ['nullable', 'date'],
                'alamat' => ['nullable', 'string'],
                'no_hp' => ['nullable', 'string', 'max:20'],
            ]),
            'siswa' => $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => array_values(array_filter([
                    'required',
                    'email',
                    'max:255',
                    $record?->user_id
                        ? Rule::unique('users', 'email')->ignore($record->user_id)
                        : Rule::unique('users', 'email'),
                ])),
                'password' => [$record ? 'nullable' : 'required', 'string', Password::min(8)],
                'phone' => ['nullable', 'string', 'max:20'],
                'status_aktif' => ['nullable', 'boolean'],
                'nis' => array_values(array_filter([
                    'required',
                    'string',
                    'max:30',
                    $record ? Rule::unique('siswa', 'nis')->ignore($record->id) : Rule::unique('siswa', 'nis'),
                ])),
                'nisn' => array_values(array_filter([
                    'nullable',
                    'string',
                    'max:30',
                    $record ? Rule::unique('siswa', 'nisn')->ignore($record->id) : Rule::unique('siswa', 'nisn'),
                ])),
                'nama_siswa' => ['required', 'string', 'max:255'],
                'jenis_kelamin' => ['nullable', 'in:Laki-laki,Perempuan'],
                'tanggal_lahir' => ['nullable', 'date'],
                'alamat' => ['nullable', 'string'],
                'no_hp' => ['nullable', 'string', 'max:20'],
                'kelas_id' => ['nullable', 'exists:kelas,id'],
            ]),
            default => [],
        };
    }

    private function persist(string $type, array $validated, mixed $record = null): void
    {
        DB::transaction(function () use ($type, $validated, $record): void {
            if ($type === 'guru') {
                $this->persistGuru($validated, $record);
                return;
            }

            if ($type === 'siswa') {
                $this->persistSiswa($validated, $record);
                return;
            }

            $model = $this->modelFor($type);
            $payload = $this->normalizePayload($type, $validated);

            if ($record) {
                $record->update($payload);
                return;
            }

            $model::create($payload);
        });
    }

    private function persistGuru(array $validated, mixed $record = null): void
    {
        $userPayload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'status_aktif' => $validated['status_aktif'] ?? false,
        ];

        if (filled($validated['password'] ?? null)) {
            $userPayload['password'] = $validated['password'];
        }

        if ($record?->user) {
            $record->user->update($userPayload);
            $user = $record->user;
        } else {
            $user = User::create($userPayload + ['role' => 'guru']);
        }

        $profilePayload = Arr::only($validated, [
            'nip',
            'nama_guru',
            'jenis_kelamin',
            'tanggal_lahir',
            'alamat',
            'no_hp',
        ]);
        $profilePayload['user_id'] = $user->id;

        if ($record) {
            $record->update($profilePayload);
            return;
        }

        Guru::create($profilePayload);
    }

    private function persistSiswa(array $validated, mixed $record = null): void
    {
        $userPayload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'status_aktif' => $validated['status_aktif'] ?? false,
        ];

        if (filled($validated['password'] ?? null)) {
            $userPayload['password'] = $validated['password'];
        }

        if ($record?->user) {
            $record->user->update($userPayload);
            $user = $record->user;
        } else {
            $user = User::create($userPayload + ['role' => 'siswa']);
        }

        $profilePayload = Arr::only($validated, [
            'nis',
            'nisn',
            'nama_siswa',
            'jenis_kelamin',
            'tanggal_lahir',
            'alamat',
            'no_hp',
            'kelas_id',
        ]);
        $profilePayload['user_id'] = $user->id;

        if ($record) {
            $record->update($profilePayload);
            return;
        }

        Siswa::create($profilePayload);
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizePayload(string $type, array $validated): array
    {
        return match ($type) {
            'tahun-ajaran' => [
                'nama_tahun_ajaran' => $validated['nama_tahun_ajaran'],
                'semester' => $validated['semester'],
                'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                'status_aktif' => (bool) ($validated['status_aktif'] ?? false),
            ],
            'kelas' => [
                'nama_kelas' => $validated['nama_kelas'],
                'tingkat' => $validated['tingkat'] ?? null,
                'jurusan' => $validated['jurusan'] ?? null,
                'kapasitas' => $validated['kapasitas'] ?? null,
                'wali_guru_id' => $validated['wali_guru_id'] ?? null,
                'tahun_ajaran_id' => $validated['tahun_ajaran_id'] ?? null,
            ],
            'mata-pelajaran' => [
                'kode_mapel' => $validated['kode_mapel'] ?? null,
                'nama_mapel' => $validated['nama_mapel'],
                'kelompok_mapel' => $validated['kelompok_mapel'] ?? null,
                'jam_mingguan' => $validated['jam_mingguan'] ?? null,
                'kkm' => $validated['kkm'] ?? null,
            ],
            'jadwal' => [
                'kelas_id' => $validated['kelas_id'],
                'guru_id' => $validated['guru_id'],
                'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
                'hari' => $validated['hari'],
                'jam_mulai' => $validated['jam_mulai'],
                'jam_selesai' => $validated['jam_selesai'],
                'ruang' => $validated['ruang'] ?? null,
                'status_aktif' => (bool) ($validated['status_aktif'] ?? false),
            ],
            default => $validated,
        };
    }
}
