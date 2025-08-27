<?php

namespace App\Enums;

class HeadingTag
{
    const H1 = 'h1';
    const H2 = 'h2';
    const H3 = 'h3';
    const H4 = 'h4';
    const H5 = 'h5';
    const H6 = 'h6';

    public static function getValues(): array {
        return [
            self::H1,
            self::H2,
            self::H3,
            self::H4,
            self::H5,
            self::H6,
        ];
    }
}
