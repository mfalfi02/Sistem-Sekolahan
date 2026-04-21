<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Sistem Sekolah') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            :root {
                color-scheme: dark;
                --bg: #081120;
                --bg2: #0f1d36;
                --card: rgba(255, 255, 255, 0.08);
                --line: rgba(255, 255, 255, 0.12);
                --text: #e2e8f0;
                --muted: #94a3b8;
                --accent: #5eead4;
            }

            * { box-sizing: border-box; }
            body {
                margin: 0;
                font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                color: var(--text);
                background:
                    radial-gradient(circle at top left, rgba(94,234,212,.16), transparent 30%),
                    radial-gradient(circle at top right, rgba(251,191,36,.14), transparent 28%),
                    linear-gradient(180deg, var(--bg), var(--bg2));
                min-height: 100vh;
            }

            a { color: inherit; }
            input, button {
                font: inherit;
            }

            header, main > div > section, main > div > article, main > div > div {
                backdrop-filter: blur(18px);
            }

            header {
                border-bottom: 1px solid var(--line);
                background: rgba(255, 255, 255, 0.05);
            }

            .mx-auto {
                max-width: 80rem;
                margin-left: auto;
                margin-right: auto;
            }

            .flex { display: flex; }
            .grid { display: grid; }
            .items-center { align-items: center; }
            .justify-between { justify-content: space-between; }
            .gap-3 { gap: 0.75rem; }
            .gap-4 { gap: 1rem; }
            .gap-6 { gap: 1.5rem; }
            .gap-8 { gap: 2rem; }
            .min-h-screen { min-height: 100vh; }
            .w-full { width: 100%; }
            .block { display: block; }
            .inline-flex { display: inline-flex; }
            .space-y-5 > * + * { margin-top: 1.25rem; }
            .space-y-8 > * + * { margin-top: 2rem; }
            .rounded-full { border-radius: 9999px; }
            .rounded-xl { border-radius: 0.75rem; }
            .rounded-2xl { border-radius: 1rem; }
            .rounded-3xl { border-radius: 1.5rem; }
            .border { border: 1px solid var(--line); }
            .border-b { border-bottom: 1px solid var(--line); }
            .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45); }
            .bg-white\/5, .bg-white\/8, .bg-white\/10, .bg-slate-900\/60 {
                background: var(--card);
            }
            .bg-teal-300 { background: var(--accent); }
            .bg-teal-200 { background: #99f6e4; }
            .bg-rose-500\/10 { background: rgba(244, 63, 94, 0.10); }
            .text-slate-100 { color: var(--text); }
            .text-slate-200 { color: #cbd5e1; }
            .text-slate-300 { color: #cbd5e1; }
            .text-slate-400 { color: var(--muted); }
            .text-slate-950 { color: #020617; }
            .text-teal-200 { color: #99f6e4; }
            .text-rose-200 { color: #fecdd3; }
            .bg-white\/5, .bg-white\/8, .bg-white\/10, .bg-slate-900\/60 {
                border: 1px solid var(--line);
            }

            main {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }

            h1, h2, h3, p { margin-top: 0; }
            h1 { font-size: 1.125rem; }
            h2 { font-size: 2.25rem; line-height: 1.05; }
            h3 { font-size: 1.5rem; }
            p { line-height: 1.7; }

            label {
                display: block;
                margin-bottom: 0.5rem;
            }

            input[type="email"], input[type="password"], input[type="text"] {
                width: 100%;
                padding: 0.85rem 1rem;
                border-radius: 1rem;
                border: 1px solid var(--line);
                background: rgba(255,255,255,0.05);
                color: var(--text);
                outline: none;
            }

            button {
                border: 0;
                cursor: pointer;
            }

            form button, .btn {
                transition: transform .15s ease, opacity .15s ease;
            }

            form button:hover, .btn:hover { transform: translateY(-1px); opacity: .96; }

            .bg-[radial-gradient(circle_at_top_left,_rgba(94,234,212,.16),_transparent_30%),radial-gradient(circle_at_top_right,_rgba(251,191,36,.14),_transparent_28%),linear-gradient(180deg,#081120,#0f1d36)] {
                min-height: 100vh;
            }

            @media (max-width: 1024px) {
                .lg\:grid-cols-2, .lg\:grid-cols-3 { grid-template-columns: 1fr !important; }
            }

            @media (min-width: 640px) {
                .sm\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
                .sm\:px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
            }

            @media (min-width: 768px) {
                .md\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            }

            @media (min-width: 1024px) {
                .lg\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
                .lg\:px-8 { padding-left: 2rem; padding-right: 2rem; }
            }
        </style>
    @endif
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(94,234,212,.16),_transparent_30%),radial-gradient(circle_at_top_right,_rgba(251,191,36,.14),_transparent_28%),linear-gradient(180deg,#081120,#0f1d36)]">
        <header class="border-b border-white/10 bg-white/5 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">{{ config('app.name', 'Sistem Sekolah') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        @if (auth()->user()->role === 'siswa')
                            <a class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15" href="{{ route('siswa.portal') }}">
                                Hasil Saya
                            </a>
                        @endif
                        @if (auth()->user()->role === 'guru')
                            <a class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15" href="{{ route('absensi.index') }}">
                                Absensi
                            </a>
                            <a class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15" href="{{ route('nilai.index') }}">
                                Nilai
                            </a>
                            <a class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15" href="{{ route('rekap.absensi') }}">
                                Rekap
                            </a>
                        @endif
                        @if (in_array(auth()->user()->role, ['admin', 'tu'], true))
                            <a class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15" href="{{ route('users.index') }}">
                                Users
                            </a>
                            <a class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15" href="{{ route('masters.index', 'mata-pelajaran') }}">
                                Mapel
                            </a>
                            <a class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15" href="{{ route('masters.index', 'jadwal') }}">
                                Jadwal
                            </a>
                            <a class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15" href="{{ route('kenaikan-kelas.index') }}">
                                Kenaikan Kelas
                            </a>
                            <a class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15" href="{{ route('rekap.absensi') }}">
                                Rekap
                            </a>
                            <a class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium hover:bg-white/15" href="{{ route('dashboard') }}">
                                Dashboard
                            </a>
                        @endif
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-sm text-slate-200">
                            {{ auth()->user()->name }} · {{ strtoupper(auth()->user()->role === 'tu' ? 'admin' : auth()->user()->role) }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-xl border border-white/10 bg-white/10 px-4 py-2 text-sm font-medium hover:bg-white/15">
                                Keluar
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>
</body>
</html>
