<?php

namespace App\Enum;

enum AgamaEnum: int
{
    const ISLAM = 0;

    const KRISTEN_KATOLIK = 1;

    const KRISTEN_PROTESTAN = 2;

    const HINDU = 3;

    const BUDDHA = 4;

    public static function list()
    {
        return [
            self::ISLAM => 'Islam',
            self::KRISTEN_KATOLIK => 'Kristen Katolik',
            self::KRISTEN_PROTESTAN => 'Kristen Protestan',
            self::HINDU => 'Hindu',
            self::BUDDHA => 'Buddha',
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
