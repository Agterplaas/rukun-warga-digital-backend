<?php

namespace App\Enum;

enum JenisKelaminEnum: int
{
    const LAKI_LAKI = 0;

    const PEREMPUAN = 1;

    public static function list()
    {
        return [
            self::LAKI_LAKI => 'LAKI LAKI',
            self::PEREMPUAN => 'PEREMPUAN',
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
