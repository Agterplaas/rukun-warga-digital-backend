<?php

namespace App\Enum;

enum StatusKawinEnum: int
{
    const BELUM_KAWIN = 0;

    const KAWIN = 1;

    const CERAI = 2;

    const JANDA = 3;

    const DUDA = 4;

    public static function list()
    {
        return [
            self::BELUM_KAWIN => 'Belum Kawin',
            self::KAWIN => 'Kawin',
            self::CERAI => 'Cerai',
            self::JANDA => 'Janda',
            self::DUDA => 'Duda',
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
