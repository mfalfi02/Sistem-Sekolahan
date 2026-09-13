<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Jadwal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #222;
            line-height: 1.45;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #111;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 4px;
        }
        .header p {
            font-size: 10px;
            color: #555;
        }
        .meta {
            margin-bottom: 14px;
            font-size: 10px;
        }
        .meta-row {
            margin-bottom: 4px;
        }
        .meta-label {
            display: inline-block;
            width: 120px;
            font-weight: bold;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 14px;
            border-spacing: 8px 0;
        }
        .summary-item {
            display: table-cell;
            width: 50%;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 12px;
            vertical-align: top;
        }
        .summary-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #666;
            margin-bottom: 6px;
        }
        .summary-value {
            font-size: 13px;
            font-weight: bold;
            color: #111;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #d9d9d9;
            padding: 7px 8px;
            vertical-align: top;
        }
        th {
            background: #f3f4f6;
            font-size: 10px;
            text-align: left;
        }
        tbody tr:nth-child(even) {
            background: #fafafa;
        }
        .center {
            text-align: center;
        }
        .footer {
            margin-top: 18px;
            font-size: 10px;
            color: #666;
            text-align: right;
        }
    </style>
</head>
<body>
    @php
        $totalJadwal = $records->count();
        $totalKelas = $records->pluck('kelas_id')->unique()->count();
    @endphp

    <div class="header">
        <h1>LAPORAN JADWAL</h1>
        <p>Sekolah Menengah Teologi Kristen Eben Heizer</p>
    </div>

    <div class="meta">
        <div class="meta-row">
            <span class="meta-label">Kelas</span>
            <span>: {{ $selectedKelas?->nama_kelas ?? 'Semua Kelas' }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Tanggal Cetak</span>
            <span>: {{ \App\Support\IndonesianDateTime::dateTime($printedAt) }}</span>
        </div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Total Jadwal</div>
            <div class="summary-value">{{ $totalJadwal }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total Kelas</div>
            <div class="summary-value">{{ $totalKelas }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="center" style="width: 5%;">No</th>
                <th style="width: 16%;">Kelas</th>
                <th style="width: 18%;">Guru</th>
                <th style="width: 18%;">Mata Pelajaran</th>
                <th style="width: 10%;">Hari</th>
                <th style="width: 15%;">Jam</th>
                <th style="width: 10%;">Ruang</th>
                <th style="width: 8%;">Aktif</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $record)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ $record->kelas?->nama_kelas ?? '-' }}</td>
                    <td>{{ $record->guru?->nama_guru ?? '-' }}</td>
                    <td>{{ $record->mataPelajaran?->nama_mapel ?? '-' }}</td>
                    <td>{{ $record->hari }}</td>
                    <td>{{ \App\Support\IndonesianDateTime::timeRange($record->jam_mulai, $record->jam_selesai) }}</td>
                    <td>{{ $record->ruang ?? '-' }}</td>
                    <td class="center">{{ $record->status_aktif ? 'Aktif' : 'Nonaktif' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="center" style="padding: 18px;">Tidak ada data jadwal.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan otomatis oleh Sistem Informasi Sekolah</p>
    </div>
</body>
</html>
