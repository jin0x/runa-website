<?php

namespace App\Enums;

class ThemeVariant
{
    const LIGHT = 'light';
    const DARK = 'dark';
    public static function getValues(): array {
        return [
            self::LIGHT,
            self::DARK,
        ];
    }
}
