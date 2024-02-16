<?php

namespace App\Enum;

enum BulanIndonesiaEnum: int
{
    const JANUARI = 1;

    const FEBRUARI = 2;

    const MARET = 3;

    const APRIL = 4;

    const MEI = 5;

    const JUNI = 6;

    const JULI = 7;

    const AGUSTUS = 8;

    const SEPTEMBER = 9;

    const OKTOBER = 10;

    const NOVEMBER = 11;

    const DESEMBER = 12;

    public static function list(): array
    {
        return [
            self::JANUARI => 'Januari',
            self::FEBRUARI => 'Februari',
            self::MARET => 'Maret',
            self::APRIL => 'April',
            self::MEI => 'Mei',
            self::JUNI => 'Juni',
            self::JULI => 'Juli',
            self::AGUSTUS => 'Agustus',
            self::SEPTEMBER => 'September',
            self::OKTOBER => 'Oktober',
            self::NOVEMBER => 'November',
            self::DESEMBER => 'Desember',
        ];
    }

    /**
     * Get label for a given key
     */
    public static function label(int $key): ?string
    {
        $list = self::list();

        return $list[$key] ?? null;
    }
}
