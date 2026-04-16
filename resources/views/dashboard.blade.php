<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Absensi dan Penilaian Sekolah</title>
    <style>
        :root {
            --bg: #081120;
            --bg-2: #0f1d36;
            --card: rgba(255, 255, 255, 0.08);
            --line: rgba(255, 255, 255, 0.14);
            --text: #eef2ff;
            --muted: #b7c3e1;
            --accent: #5eead4;
            --accent-2: #fbbf24;
            --danger: #fb7185;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(94, 234, 212, 0.18), transparent 35%),
                radial-gradient(circle at top right, rgba(251, 191, 36, 0.16), transparent 30%),
                linear-gradient(180deg, var(--bg), var(--bg-2));
            min-height: 100vh;
        }

        .wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 40px 20px 64px;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 24px;
            align-items: stretch;
        }

        .panel {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 28px;
            backdrop-filter: blur(18px);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.28);
        }

        .hero-copy {
            padding: 32px;
        }

        .eyebrow {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--line);
            color: var(--muted);
            font-size: 14px;
        }

        h1 {
            margin: 18px 0 14px;
            font-size: clamp(2.2rem, 5vw, 4.6rem);
            line-height: 0.98;
            letter-spacing: -0.04em;
        }

        .lead {
            margin: 0;
            max-width: 62ch;
            color: var(--muted);
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 26px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 13px 18px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: var(--accent);
            color: #05202b;
        }

        .btn-secondary {
            background: rgba(255,255,255,0.06);
            border-color: var(--line);
            color: var(--text);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            padding: 24px;
        }

        .stat {
            padding: 18px;
            border-radius: 20px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--line);
        }

        .stat strong {
            display: block;
            font-size: 1.8rem;
            margin-bottom: 4px;
        }

        .section {
            margin-top: 24px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .card {
            padding: 22px;
            border-radius: 24px;
            background: rgba(255,255,255,0.06);
            border: 1px solid var(--line);
        }

        .card h3 {
            margin: 0 0 8px;
            font-size: 1.05rem;
        }

        .card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.65;
            font-size: 0.96rem;
        }

        .flow {
            margin-top: 24px;
            padding: 24px;
        }

        .flow h2 {
            margin: 0 0 14px;
            font-size: 1.3rem;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 10px;
        }

        .step {
            padding: 16px;
            border-radius: 18px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--line);
            min-height: 116px;
        }

        .step .num {
            display: inline-flex;
            width: 30px;
            height: 30px;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(94, 234, 212, 0.18);
            color: var(--accent);
            font-weight: 800;
            margin-bottom: 10px;
        }

        .step span {
            display: block;
            color: var(--muted);
            line-height: 1.55;
            font-size: 0.95rem;
        }

        @media (max-width: 960px) {
            .hero, .section, .steps {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 640px) {
            .wrap {
                padding: 18px 14px 40px;
            }

            .hero-copy, .stats, .flow {
                padding: 20px;
            }

            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <section class="hero">
            <div class="panel hero-copy">
                <div class="eyebrow">Sistem Informasi Sekolah</div>
                <h1>Absensi dan penilaian yang rapi, cepat, dan terpusat.</h1>
                <p class="lead">
                    Platform ini menjadi titik awal sistem sekolah untuk guru, siswa, dan admin. Guru mengisi absensi dan nilai,
                    siswa melihat hasil belajar, sementara admin mengelola user, mata pelajaran, dan laporan akhir.
                </p>
                <div class="actions">
                    <a class="btn btn-primary" href="#fitur">Lihat Modul</a>
                    <a class="btn btn-secondary" href="#alur">Alur Sistem</a>
                </div>
            </div>

            <div class="panel stats">
                <div class="stat">
                    <strong>3 Role</strong>
                    <span>Guru, siswa, dan admin</span>
                </div>
                <div class="stat">
                    <strong>1 Data</strong>
                    <span>Sumber informasi terpusat</span>
                </div>
                <div class="stat">
                    <strong>Auto Rekap</strong>
                    <span>Absensi dan nilai akhir</span>
                </div>
                <div class="stat">
                    <strong>Export</strong>
                    <span>PDF dan Excel laporan</span>
                </div>
            </div>
        </section>

        <section class="section" id="fitur">
            <div class="card">
                <h3>Guru</h3>
                <p>Mengisi absensi, memasukkan nilai, melihat jadwal, dan memantau rekap kelas yang diajar.</p>
            </div>
            <div class="card">
                <h3>Siswa</h3>
                <p>Melihat jadwal, riwayat kehadiran, nilai per mapel, dan hasil akhir pembelajaran.</p>
            </div>
            <div class="card">
                <h3>Admin</h3>
                <p>Mengelola user, mata pelajaran, dan pengaturan sistem.</p>
            </div>
        </section>

        <section class="panel flow" id="alur">
            <h2>Alur Utama Sistem</h2>
            <div class="steps">
                <div class="step">
                    <div class="num">1</div>
                    <span>Admin mengelola user dan mata pelajaran.</span>
                </div>
                <div class="step">
                    <div class="num">2</div>
                    <span>Guru login dan melihat jadwal mengajar yang sudah ditentukan.</span>
                </div>
                <div class="step">
                    <div class="num">3</div>
                    <span>Guru menginput absensi siswa sesuai pertemuan atau jadwal.</span>
                </div>
                <div class="step">
                    <div class="num">4</div>
                    <span>Guru memasukkan nilai berdasarkan jenis penilaian.</span>
                </div>
                <div class="step">
                    <div class="num">5</div>
                    <span>Sistem merekap otomatis dan membentuk nilai akhir.</span>
                </div>
                <div class="step">
                    <div class="num">6</div>
                    <span>Siswa melihat hasil, lalu admin mencetak laporan resmi.</span>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
