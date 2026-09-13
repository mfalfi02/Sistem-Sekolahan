<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Nilai</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
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
            margin: 5px 0;
        }
        .info-label {
            display: inline-block;
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
        .nilai-tinggi { color: #22c55e; font-weight: bold; }
        .nilai-sedang { color: #f97316; font-weight: bold; }
        .nilai-rendah { color: #ef4444; font-weight: bold; }
        td.nilai-number {
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @php
        $tahunAjaranLabel = $tahunAjaranAktif?->nama_tahun_ajaran ?? '-';
    @endphp

    <div class="header">
        <h1>LAPORAN REKAP NILAI</h1>
        <p>Sekolah Menengah Teologi Kristen Eben Heizer</p>
    </div>

    <div class="info">
        @if ($selectedKelas)
            <div class="info-row">
                <span class="info-label">Kelas</span>
                <span>: {{ $selectedKelas->nama_kelas }}</span>
            </div>
        @endif
        @if ($selectedMapel)
            <div class="info-row">
                <span class="info-label">Mata Pelajaran</span>
                <span>: {{ $selectedMapel->nama_mapel }}</span>
            </div>
        @endif
        <div class="info-row">
            <span class="info-label">Periode</span>
            <span>: Tahun Ajaran {{ $tahunAjaranLabel }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Cetak</span>
            <span>: {{ \App\Support\IndonesianDateTime::dateTime(now('Asia/Jakarta')) }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Nama Siswa</th>
                <th style="width: 15%;">Kelas</th>
                <th style="width: 20%;">Mata Pelajaran</th>
                <th style="width: 10%;">Nilai Akhir</th>
                <th style="width: 15%;">Absensi</th>
                <th style="width: 15%;">Kontribusi 10%</th>
                <th style="width: 15%;">Grade</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $record)
                @php
                    $attendanceContribution = round(((float) ($record->nilai_absensi ?? 0)) * 0.10, 2);
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $record->siswa?->nama_siswa ?? '-' }}</td>
                    <td>{{ $record->kelas?->nama_kelas ?? '-' }}</td>
                    <td>{{ $record->mataPelajaran?->nama_mapel ?? '-' }}</td>
                    <td class="nilai-number">
                        @php
                            $nilai = (float) $record->nilai_akhir;
                            $nilaiClass = $nilai >= 85 ? 'nilai-tinggi' : ($nilai >= 70 ? 'nilai-sedang' : 'nilai-rendah');
                        @endphp
                        <span class="{{ $nilaiClass }}">{{ number_format($nilai, 2, ',', '.') }}</span>
                    </td>
                    <td style="text-align: center;">
                        {{ number_format((float) ($record->persentase_absensi ?? 0), 2, ',', '.') }}%
                        <br>
                        <small>{{ (int) ($record->absensi_hadir ?? 0) }}/{{ (int) ($record->absensi_total ?? 0) }}</small>
                    </td>
                    <td style="text-align: center;">
                        {{ number_format($attendanceContribution, 2, ',', '.') }}
                    </td>
                    <td style="text-align: center;">
                        @if ($record->nilai_akhir >= 90)
                            <strong>A</strong>
                        @elseif ($record->nilai_akhir >= 80)
                            <strong>B</strong>
                        @elseif ($record->nilai_akhir >= 70)
                            <strong>C</strong>
                        @elseif ($record->nilai_akhir >= 60)
                            <strong>D</strong>
                        @else
                            <strong>E</strong>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">Tidak ada data nilai</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh Sistem Informasi Sekolah</p>
        <p style="margin-top: 10px;">{{ \App\Support\IndonesianDateTime::dayName(now('Asia/Jakarta')) }}, {{ \App\Support\IndonesianDateTime::date(now('Asia/Jakarta')) }}</p>
    </div>
</body>
</html>
