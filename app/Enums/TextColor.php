<?php

namespace App\Enums;

class TextColor
{
    public const LIGHT = 'light';
    public const DARK = 'dark';
    public const GREEN_SOFT = 'green-soft';
    public const GREEN_NEON = 'green-neon';
    public const GRADIENT = 'gradient';
    public const GRAY = 'gray';

    public static function getValues(): array
    {
        return [
            self::LIGHT,
            self::DARK,
            self::GREEN_SOFT,
            self::GREEN_NEON,
            self::GRADIENT,
            self::GRAY,
        ];
    }
}