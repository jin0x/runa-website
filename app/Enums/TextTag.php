<?php

namespace App\Enums;

class TextTag
{
    public const A = 'a';
    public const P = 'p';
    public const SPAN = 'span';
    public const DIV = 'div';
    public const LI = 'li';

    public static function getValues(): array
    {
        return [
            self::A,
            self::P,
            self::SPAN,
            self::DIV,
            self::LI,
        ];
    }
}
