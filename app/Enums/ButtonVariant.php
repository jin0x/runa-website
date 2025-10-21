<?php

namespace App\Enums;

class ButtonVariant
{
    const PRIMARY = 'primary';
    const SECONDARY = 'secondary';
    const LIGHT = 'light';
    const DARK = 'dark';
    const NAV = 'nav';

    public static function getValues(): array {
        return [
            self::PRIMARY,
            self::SECONDARY,
            self::LIGHT,
            self::DARK,
            self::NAV,
        ];
    }
}
