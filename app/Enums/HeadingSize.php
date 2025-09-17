<?php

namespace App\Enums;

class HeadingSize
{
    const H1 = 'heading-1';
    const H2 = 'heading-2';
    const H3 = 'heading-3';
    const H4 = 'heading-4';
    const H5 = 'heading-5';
    const H6 = 'heading-6';
    const DISPLAY_LARGE = 'text-display-large';
    const DISPLAY_MEDIUM = 'text-display-medium';
    const DISPLAY_SMALL = 'text-display-small';
    const DISPLAY_EXTRA_LARGE = 'text-display-xl'; // Heading - 120px

    public static function getValues(): array {
        return [
            self::H1,
            self::H2,
            self::H3,
            self::H4,
            self::H5,
            self::H6,
            self::DISPLAY_LARGE,
            self::DISPLAY_MEDIUM,
            self::DISPLAY_SMALL,
            self::DISPLAY_EXTRA_LARGE,
        ];
    }
}
