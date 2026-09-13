<?php

namespace App\Support;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Support\Carbon;

class IndonesianDateTime
{
    /**
     * Format tanggal ke gaya Indonesia.
     */
    public static function date(CarbonInterface|DateTimeInterface|string|null $value): string
    {
        $date = self::carbon($value);

        if (! $date) {
            return '-';
        }

        return $date->format('d').' '.self::monthName((int) $date->format('n')).' '.$date->format('Y');
    }

    /**
     * Format jam ke gaya Indonesia.
     */
    public static function time(CarbonInterface|DateTimeInterface|string|null $value): string
    {
        $date = self::carbon($value);

        if (! $date) {
            return '-';
        }

        return $date->format('H:i').' WIB';
    }

    /**
     * Format tanggal dan jam ke gaya Indonesia.
     */
    public static function dateTime(CarbonInterface|DateTimeInterface|string|null $value): string
    {
        $date = self::carbon($value);

        if (! $date) {
            return '-';
        }

        return self::date($date).' '.self::time($date);
    }

    /**
     * Format rentang jam.
     */
    public static function timeRange(CarbonInterface|DateTimeInterface|string|null $start, CarbonInterface|DateTimeInterface|string|null $end): string
    {
        $startTime = self::time($start);
        $endTime = self::time($end);

        if ($startTime === '-' && $endTime === '-') {
            return '-';
        }

        return trim($startTime.' - '.$endTime);
    }

    /**
     * Format bulan dan tahun.
     */
    public static function monthYear(CarbonInterface|DateTimeInterface|string|null $value): string
    {
        $date = self::carbon($value);

        if (! $date) {
            return '-';
        }

        return self::monthName((int) $date->format('n')).' '.$date->format('Y');
    }

    /**
     * Format nama hari ke bahasa Indonesia.
     */
    public static function dayName(CarbonInterface|DateTimeInterface|string|null $value): string
    {
        $date = self::carbon($value);

        if (! $date) {
            return '-';
        }

        return match ((int) $date->format('N')) {
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
            default => '-',
        };
    }

    private static function carbon(CarbonInterface|DateTimeInterface|string|null $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value->copy()->timezone('Asia/Jakarta');
        }

        if ($value instanceof DateTimeInterface) {
            return Carbon::instance($value)->timezone('Asia/Jakarta');
        }

        return Carbon::parse($value, 'Asia/Jakarta');
    }

    private static function monthName(int $month): string
    {
        return match ($month) {
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
            default => '-',
        };
    }
}
