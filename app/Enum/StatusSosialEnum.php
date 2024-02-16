<?php

namespace App\Enum;

enum StatusSosialEnum: int
{
    const MENENGAH_KE_ATAS = 0;

    const MENENGAH_KE_BAWAH = 1;

    const KURANG_MAMPU = 2;

    public static function list()
    {
        return [
            self::MENENGAH_KE_ATAS => 'Menegah ke atas',
            self::MENENGAH_KE_BAWAH => 'Menengah ke bawah',
            self::KURANG_MAMPU => 'Kurang mampu',
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
