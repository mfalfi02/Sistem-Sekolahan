@props([
    'name',
    'value' => '',
    'placeholder' => '',
    'inputClass' => '',
    'note' => null,
    'showNote' => false,
    'autocomplete' => 'current-password',
])

@php
    $inputId = 'password-'.\Illuminate\Support\Str::slug($name.'-'.\Illuminate\Support\Str::random(6));
    $buttonId = $inputId.'-toggle';
    $iconId = $inputId.'-icon';
    $textId = $inputId.'-text';
@endphp

<div class="relative">
    <input
        id="{{ $inputId }}"
        type="password"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        autocomplete="{{ $autocomplete }}"
        {{ $attributes->merge(['class' => trim('w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 pr-14 text-white outline-none placeholder:text-slate-500 focus:border-teal-300/50 '.$inputClass)]) }}
    >
    <button
        type="button"
        id="{{ $buttonId }}"
        class="absolute inset-y-0 right-2 my-2 inline-flex items-center justify-center rounded-xl border border-white/10 bg-slate-950/20 px-3 text-slate-300 transition hover:bg-white/10 hover:text-white"
        aria-label="Tampilkan password"
        aria-pressed="false"
    >
        <svg id="{{ $iconId }}" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M2.75 12s3.75-7.25 9.25-7.25S21.25 12 21.25 12 17.5 19.25 12 19.25 2.75 12 2.75 12Z" />
            <circle cx="12" cy="12" r="2.75" />
        </svg>
    </button>
</div>

@if ($showNote && filled($note))
    <p class="mt-2 text-xs text-slate-400">{{ $note }}</p>
@endif

<script>
    (function () {
        const input = document.getElementById(@json($inputId));
        const button = document.getElementById(@json($buttonId));
        const icon = document.getElementById(@json($iconId));

        if (!input || !button || !icon) {
            return;
        }

        const eye = '<path d="M2.75 12s3.75-7.25 9.25-7.25S21.25 12 21.25 12 17.5 19.25 12 19.25 2.75 12 2.75 12Z" /><circle cx="12" cy="12" r="2.75" />';
        const eyeOff = '<path d="M4.5 4.5 19.5 19.5" /><path d="M9.75 9.75A3.1 3.1 0 0 0 12 15.1a3.1 3.1 0 0 0 2.25-.95" /><path d="M6.2 6.2C4.36 7.73 3.1 9.77 2.75 12c0 0 3.75 7.25 9.25 7.25 1.1 0 2.13-.2 3.08-.57" /><path d="M10.8 5.2c.39-.06.79-.1 1.2-.1 5.5 0 9.25 7.25 9.25 7.25-.46 2.9-2.35 5.42-4.85 6.83" />';

        button.addEventListener('click', function () {
            const hidden = input.type === 'password';

            input.type = hidden ? 'text' : 'password';
            button.setAttribute('aria-label', hidden ? 'Sembunyikan password' : 'Tampilkan password');
            button.setAttribute('aria-pressed', String(hidden));
            icon.innerHTML = hidden ? eyeOff : eye;
        });
    })();
</script>
