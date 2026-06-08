@extends('layouts.admin')

@section('admin_title', ($schema['title'] ?? 'Data Master'))

@section('content')
<div class="space-y-8">
    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur-xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">Master Data</p>
                <h2 class="mt-3 text-3xl font-semibold">
                    {{ $mode === 'create' ? 'Tambah' : 'Ubah' }} {{ $schema['title'] }}
                </h2>
                <p class="mt-2 text-slate-300">Isi data dengan benar agar absensi dan penilaian tersusun rapi.</p>
            </div>
            <a href="{{ route('masters.index', $type) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15">
                Kembali
            </a>
        </div>
    </section>

    <form method="POST" action="{{ $mode === 'create' ? route('masters.store', $type) : route('masters.update', [$type, $record->id]) }}" class="space-y-6">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        @foreach ($schema['sections'] as $section)
            <section class="rounded-3xl border border-white/10 bg-slate-900/60 p-8 shadow-2xl">
                <h3 class="text-xl font-semibold">{{ $section['title'] }}</h3>
                <div class="mt-6 grid gap-5 md:grid-cols-2">
                    @foreach ($section['fields'] as $field)
                        @php
                            $fieldName = $field['name'];
                            $currentValue = old($fieldName, data_get($record, $fieldName));
                            $fieldType = $field['type'] ?? 'text';
                            $fieldOptions = $options[$fieldName] ?? ($field['options'] ?? []);
                            $isRequired = (bool) ($field['required'] ?? false);
                        @endphp
                        <div class="{{ $fieldType === 'textarea' ? 'md:col-span-2' : '' }}">
                            <label class="mb-2 block text-sm font-medium text-slate-300" for="{{ $fieldName }}">
                                {{ $field['label'] }}
                                @if ($isRequired)
                                    <span class="text-rose-300">*</span>
                                @endif
                            </label>

                            @if ($fieldType === 'textarea')
                                <textarea
                                    id="{{ $fieldName }}"
                                    name="{{ $fieldName }}"
                                    rows="4"
                                    class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none placeholder:text-slate-500 focus:border-teal-300/50"
                                >{{ $currentValue }}</textarea>
                            @elseif ($fieldType === 'select')
                                <select
                                    id="{{ $fieldName }}"
                                    name="{{ $fieldName }}"
                                    class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none focus:border-teal-300/50"
                                >
                                    <option value="">Pilih {{ $field['label'] }}</option>
                                    @foreach ($fieldOptions as $value => $label)
                                        <option value="{{ $value }}" @selected((string) $currentValue === (string) $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            @elseif ($fieldType === 'checkbox')
                                <input type="hidden" name="{{ $fieldName }}" value="0">
                                <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200">
                                    <input
                                        id="{{ $fieldName }}"
                                        type="checkbox"
                                        name="{{ $fieldName }}"
                                        value="1"
                                        class="rounded border-white/20 bg-white/10 text-teal-400"
                                        @checked((bool) $currentValue)
                                    >
                                    {{ $field['hint'] ?? 'Centang jika aktif' }}
                                </label>
                            @else
                                <input
                                    id="{{ $fieldName }}"
                                    type="{{ $fieldType }}"
                                    name="{{ $fieldName }}"
                                    value="{{ $fieldType === 'password' ? '' : $currentValue }}"
                                    placeholder="{{ $field['hint'] ?? '' }}"
                                    class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none placeholder:text-slate-500 focus:border-teal-300/50"
                                >
                                @if ($fieldType === 'password' && $mode === 'edit')
                                    <p class="mt-2 text-xs text-slate-400">Kosongkan jika password tidak diubah.</p>
                                @endif
                            @endif

                            @error($fieldName)
                                <p class="mt-2 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                            @if (! empty($field['hint']) && $fieldType !== 'password')
                                <p class="mt-2 text-xs text-slate-400">{{ $field['hint'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="flex flex-wrap gap-3">
            <button class="rounded-2xl bg-teal-300 px-5 py-3 font-semibold text-slate-950 hover:bg-teal-200">
                {{ $mode === 'create' ? 'Simpan Data' : 'Perbarui Data' }}
            </button>
            <a href="{{ route('masters.index', $type) }}" class="rounded-2xl border border-white/10 bg-white/5 px-5 py-3 font-semibold text-slate-200 hover:bg-white/15">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
