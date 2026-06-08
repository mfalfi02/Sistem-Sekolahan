<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Absensi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10px;
            margin: 2px 0;
        }
        .info {
            margin-bottom: 15px;
            font-size: 10px;
        }
        .info-row {
            display: flex;
            margin: 5px 0;
        }
        .info-label {
            width: 120px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
        .status-hadir { color: #22c55e; font-weight: bold; }
        .status-sakit { color: #f97316; font-weight: bold; }
        .status-izin { color: #3b82f6; font-weight: bold; }
        .status-alfa { color: #ef4444; font-weight: bold; }
        .status-terlambat { color: #eab308; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 LAPORAN REKAP ABSENSI</h1>
        <p>Sistem Informasi Sekolah</p>
    </div>

    <div class="info">
        @if ($selectedKelas)
            <div class="info-row">
                <span class="info-label">Kelas</span>
                <span>: {{ $selectedKelas->nama_kelas }}</span>
            </div>
        @endif
        <div class="info-row">
            <span class="info-label">Periode</span>
            <span>: {{ \Carbon\Carbon::parse($tanggalDari)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalSampai)->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Cetak</span>
            <span>: {{ now()->translatedFormat('d F Y H:i') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 25%;">Nama Siswa</th>
                <th style="width: 15%;">Kelas</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 25%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $record)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->tanggal_absen)->format('d M Y') }}</td>
                    <td>{{ $record->siswa?->nama_siswa ?? '-' }}</td>
                    <td>{{ $record->siswa?->kelas?->nama_kelas ?? '-' }}</td>
                    <td>
                        <span class="status-{{ $record->status_kehadiran }}">
                            @switch($record->status_kehadiran)
                                @case('hadir')
                                    ✓ Hadir
                                    @break
                                @case('sakit')
                                    ⚠ Sakit
                                    @break
                                @case('izin')
                                    → Izin
                                    @break
                                @case('alfa')
                                    ✕ Alfa
                                    @break
                                @case('terlambat')
                                    ⏱ Terlambat
                                    @break
                            @endswitch
                        </span>
                    </td>
                    <td>{{ $record->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data absensi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh Sistem Informasi Sekolah</p>
        <p style="margin-top: 10px;">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
</body>
</html>
