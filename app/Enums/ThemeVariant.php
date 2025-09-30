<?php

namespace App\Enums;

class ThemeVariant
{
    const LIGHT = 'light';
    const DARK = 'dark';

    const GREEN = 'green';

    const PURPLE = 'purple';
    public static function getValues(): array {
        return [
            self::LIGHT,
            self::DARK,
            self::GREEN,
            self::PURPLE,
        ];
    }
}
