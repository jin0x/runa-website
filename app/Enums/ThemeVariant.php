<?php

namespace App\Enums;

class ThemeVariant
{
    public const LIGHT = 'light';
    public const DARK = 'dark';
    public const GREEN = 'green';
    public const PURPLE = 'purple';
    public const GREEN_GRADIENT = 'green-gradient';
    public static function getValues(): array
    {
        return [
            self::LIGHT,
            self::DARK,
            self::GREEN,
            self::PURPLE,
            self::GREEN_GRADIENT,
        ];
    }
}
