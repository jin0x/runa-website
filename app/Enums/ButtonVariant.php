<?php

namespace App\Enums;

class ButtonVariant
{
    const GREEN = 'green';
    const PURPLE = 'purple';
    const TRANSPARENT = 'transparent';
    const LIGHT = 'light';
    const DARK = 'dark';

    public static function getValues(): array {
        return [
            self::GREEN,
            self::PURPLE,
            self::TRANSPARENT,
            self::LIGHT,
            self::DARK,
        ];
    }
}
