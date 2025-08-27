<?php

namespace App\Enums;

class FontType
{
    const SANS = 'font-sans';
    const SERIF = 'font-serif';
    const MONO = 'font-mono';

    public static function getValues(): array {
        return [
            self::SANS,
            self::SERIF,
            self::MONO,
        ];
    }
}
