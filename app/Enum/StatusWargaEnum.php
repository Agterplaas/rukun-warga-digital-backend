<?php

namespace App\Enum;

enum StatusWargaEnum: int
{
    const WARGA_RW_12 = 0;

    const WARGA_LUAR = 1;

    public static function list()
    {
        return [
            self::WARGA_RW_12 => 'Warga RW 12',
            self::WARGA_LUAR => 'Warga Luar',
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
