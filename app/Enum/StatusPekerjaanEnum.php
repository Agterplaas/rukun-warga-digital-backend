<?php

namespace App\Enum;

enum StatusPekerjaanEnum: int
{
    const BEKERJA = 0;

    const MAHASISWA_I = 1;

    const PENGANGGURAN = 2;

    const PENGANGGURAN_BERPENDIDIKAN_TINGGI = 3;

    const PENSIUNAN = 4;

    const PELAJAR = 5;

    const TIDAK_BEKERJA = 6;

    const BERKEBUTUHAN_KHUSUS = 7;

    public static function list()
    {
        return [
            self::BEKERJA => 'Bekerja',
            self::MAHASISWA_I => 'Mahasiswa/i',
            self::PENGANGGURAN => 'Pengangguran',
            self::PENGANGGURAN_BERPENDIDIKAN_TINGGI => 'Pengangguran Berpendidikan Tinggi',
            self::PENSIUNAN => 'Pensiunan',
            self::PELAJAR => 'Pelajar',
            self::TIDAK_BEKERJA => 'Tidak Bekerja (Tidak Mencari Pekerjaan)',
            self::BERKEBUTUHAN_KHUSUS => 'Berkebutuhan Khusus',
        ];
    }

    /**
     * Get relationship label
     *
     * @param  int  $key
     * @return string
     */
    public static function label($key)
    {
        $list = self::list();

        if (isset($list[$key])) {
            return $list[$key];
        }

        return null;
    }
}
