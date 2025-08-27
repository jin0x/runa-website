<?php

namespace App\Enums;

class TextTag
{
    const P = 'p';
    const SPAN = 'span';
    const DIV = 'div';
    const LI = 'li';

    public static function getValues(): array {
        return [
            self::P,
            self::SPAN,
            self::DIV,
            self::LI,
        ];
    }
}
