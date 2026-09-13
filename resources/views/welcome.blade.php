<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sekolah Menengah Teologi Kristen Eben Heizer') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            :root {
                color-scheme: dark;
                --bg: #07111f;
                --bg-2: #0c1b33;
                --panel: rgba(8, 23, 44, 0.78);
                --line: rgba(255, 255, 255, 0.10);
                --text: #e5eefc;
                --muted: #93a4bf;
                --accent: #7dd3fc;
                --accent-2: #f59e0b;
            }

            * { box-sizing: border-box; }
            html, body { min-height: 100%; }
            body {
                margin: 0;
                font-family: "Instrument Sans", ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                color: var(--text);
                background:
                    radial-gradient(circle at top left, rgba(125, 211, 252, 0.20), transparent 28%),
                    radial-gradient(circle at top right, rgba(245, 158, 11, 0.16), transparent 24%),
                    linear-gradient(180deg, var(--bg), var(--bg-2));
            }

            a { color: inherit; text-decoration: none; }
            .wrap { min-height: 100vh; padding: 24px; }
            .shell { max-width: 1120px; margin: 0 auto; }
            .topbar, .hero, .stats, .cards { display: grid; gap: 20px; }
            .topbar {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                align-items: center;
                padding: 8px 0 28px;
            }
            .brand { display: inline-flex; align-items: center; gap: 14px; }
            .brand-badge {
                width: 50px; height: 50px; border-radius: 16px;
                display: grid; place-items: center;
                background: linear-gradient(145deg, rgba(125, 211, 252, 0.24), rgba(245, 158, 11, 0.18));
                border: 1px solid var(--line);
                box-shadow: 0 18px 40px rgba(0, 0, 0, 0.28);
            }
            .brand-title { font-size: 1.05rem; font-weight: 700; letter-spacing: 0.02em; }
            .brand-subtitle { color: var(--muted); font-size: 0.92rem; margin-top: 4px; }
            .actions { justify-self: end; display: flex; gap: 12px; flex-wrap: wrap; }
            .btn {
                display: inline-flex; align-items: center; justify-content: center;
                min-height: 46px; padding: 0 18px; border-radius: 14px;
                border: 1px solid var(--line); font-weight: 600;
                transition: transform 160ms ease, background 160ms ease, border-color 160ms ease;
            }
            .btn:hover { transform: translateY(-1px); }
            .btn-primary {
                background: linear-gradient(135deg, rgba(125, 211, 252, 0.22), rgba(14, 165, 233, 0.32));
                border-color: rgba(125, 211, 252, 0.36);
            }
            .btn-ghost { background: rgba(255,255,255,0.04); }
            .hero {
                grid-template-columns: minmax(0, 1.2fr) minmax(300px, 0.8fr);
                align-items: center;
                padding: 34px;
                border: 1px solid var(--line);
                border-radius: 28px;
                background: linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.03));
                box-shadow: 0 24px 80px rgba(0, 0, 0, 0.24);
                backdrop-filter: blur(18px);
            }
            .eyebrow {
                display: inline-flex; align-items: center; gap: 10px;
                padding: 8px 14px; border-radius: 999px;
                background: rgba(255,255,255,0.05);
                color: var(--accent); font-size: 0.84rem; font-weight: 700;
                letter-spacing: 0.12em; text-transform: uppercase;
            }
            h1 {
                margin: 18px 0 14px;
                font-size: clamp(2.4rem, 5vw, 4.8rem);
                line-height: 0.98;
                letter-spacing: -0.05em;
            }
            .lead { max-width: 60ch; color: #c7d3e8; font-size: 1.05rem; line-height: 1.75; }
            .meta { display: flex; gap: 14px; flex-wrap: wrap; margin-top: 24px; }
            .chip {
                padding: 10px 14px; border-radius: 999px;
                border: 1px solid var(--line); background: rgba(255,255,255,0.04);
                color: #dce7f7; font-size: 0.92rem;
            }
            .card-stack {
                display: grid; gap: 14px;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .panel, .metric {
                border: 1px solid var(--line);
                background: var(--panel);
                border-radius: 22px;
                backdrop-filter: blur(16px);
                box-shadow: 0 16px 40px rgba(0, 0, 0, 0.18);
            }
            .panel { padding: 20px; }
            .metric { padding: 18px; }
            .metric strong { display: block; font-size: 1.8rem; line-height: 1; }
            .metric span { display: block; margin-top: 8px; color: var(--muted); font-size: 0.92rem; }
            .section-title { margin: 34px 0 14px; font-size: 1.05rem; letter-spacing: 0.08em; text-transform: uppercase; color: #bed0ec; }
            .cards { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .feature { padding: 22px; }
            .feature h2 { margin: 14px 0 8px; font-size: 1.1rem; }
            .feature p { margin: 0; color: var(--muted); line-height: 1.7; }
            .icon {
                width: 42px; height: 42px; border-radius: 14px;
                display: grid; place-items: center;
                background: rgba(125, 211, 252, 0.12);
                border: 1px solid rgba(125, 211, 252, 0.18);
                color: var(--accent);
            }
            .login-box {
                margin-top: 24px;
                padding: 22px 24px;
                border-radius: 22px;
                border: 1px solid rgba(245, 158, 11, 0.22);
                background: linear-gradient(135deg, rgba(245, 158, 11, 0.12), rgba(255, 255, 255, 0.04));
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                flex-wrap: wrap;
            }
            .login-box p { margin: 0; color: #dfe8f7; }

            @media (max-width: 900px) {
                .topbar, .hero, .cards { grid-template-columns: 1fr; }
                .actions { justify-self: start; }
            }
        </style>
    @endif
</head>
<body>
@php
    $appName = config('app.name', 'Sekolah Menengah Teologi Kristen Eben Heizer');
    $icon = function (string $name, string $class = 'h-5 w-5') {
        $paths = [
            'book' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5Z"/>',
            'shield' => '<path d="M12 22s7-3.5 7-11V5l-7-2-7 2v6c0 7.5 7 11 7 11Z"/>',
            'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.8"/><path d="M16 3.2a4 4 0 0 1 0 7.6"/>',
            'calendar' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18"/>',
            'chart' => '<path d="M3 3v18h18"/><path d="M7 16V9"/><path d="M12 16V5"/><path d="M17 16v-3"/>',
            'arrow' => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
            'spark' => '<path d="M12 3l1.8 4.6L18 9.4l-4.2 1.8L12 16l-1.8-4.8L6 9.4l4.2-1.8L12 3Z"/>',
        ];

        return '<svg class="'.$class.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.($paths[$name] ?? $paths['book']).'</svg>';
    };
@endphp
<div class="wrap">
    <div class="shell">
        <header class="topbar">
            <a class="brand" href="{{ url('/') }}">
                <span class="brand-badge">{!! $icon('book', 'h-6 w-6') !!}</span>
                <span>
                    <span class="brand-title">{{ $appName }}</span>
                    <span class="brand-subtitle">Platform akademik sekolah yang rapi, cepat, dan terintegrasi.</span>
                </span>
            </a>

            <div class="actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Buka Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk Sistem</a>
                @endauth
            </div>
        </header>

        <main class="hero">
            <section>
                <div class="eyebrow">{!! $icon('spark', 'h-4 w-4') !!} Sekolah Menengah Teologi Kristen Eben Heizer</div>
                <h1>Kelola absensi, nilai, jadwal, dan rekap sekolah dalam satu tempat.</h1>
                <p class="lead">
                    {{ $appName }} membantu guru, siswa, TU, dan admin bekerja lebih cepat dengan tampilan yang bersih,
                    data yang terpusat, dan alur kerja yang mudah dipahami.
                </p>

                <div class="meta">
                    <span class="chip">Absensi harian</span>
                    <span class="chip">Rekap nilai</span>
                    <span class="chip">Jadwal pelajaran</span>
                    <span class="chip">Cetak Excel & PDF</span>
                </div>

                <div class="login-box">
                    <p>Masuk untuk mengelola data sesuai peran Anda.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">Lanjut ke Login</a>
                </div>
            </section>

            <section class="card-stack">
                <div class="metric">
                    <strong>4</strong>
                    <span>Role utama: admin, TU, guru, dan siswa</span>
                </div>
                <div class="metric">
                    <strong>1</strong>
                    <span>Data terpusat untuk semua aktivitas sekolah</span>
                </div>
                <div class="metric">
                    <strong>2</strong>
                    <span>Output laporan siap cetak ke Excel dan PDF</span>
                </div>
                <div class="metric">
                    <strong>24/7</strong>
                    <span>Akses login sesuai kebutuhan sekolah</span>
                </div>
            </section>
        </main>

        <section>
            <h2 class="section-title">Fitur Utama</h2>
            <div class="cards">
                <article class="panel feature">
                    <span class="icon">{!! $icon('calendar') !!}</span>
                    <h2>Absensi & Jadwal</h2>
                    <p>Pengisian kehadiran lebih cepat dengan data kelas, mata pelajaran, dan guru yang saling terhubung.</p>
                </article>
                <article class="panel feature">
                    <span class="icon">{!! $icon('chart') !!}</span>
                    <h2>Rekap Otomatis</h2>
                    <p>Rekap absensi dan nilai siap digunakan untuk laporan sekolah tanpa input ulang yang berulang.</p>
                </article>
                <article class="panel feature">
                    <span class="icon">{!! $icon('users') !!}</span>
                    <h2>Akses Sesuai Peran</h2>
                    <p>Setiap pengguna melihat menu dan data yang relevan, sehingga tampilan tetap sederhana dan jelas.</p>
                </article>
            </div>
        </section>
    </div>
</div>
</body>
</html>
