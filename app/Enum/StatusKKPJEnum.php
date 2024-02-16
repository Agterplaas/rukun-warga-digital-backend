<?php

namespace App\Enum;

enum StatusKKPJEnum: int
{
    const ANGGOTA = 0;

    const KEPALA_KELUARGA = 1;

    const PENANGGUNG_JAWAB = 2;

    public static function list()
    {
        return [
            self::ANGGOTA => 'Anggota',
            self::KEPALA_KELUARGA => 'Kepala Keluarga',
            self::PENANGGUNG_JAWAB => 'Penanggung Jawab',
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
