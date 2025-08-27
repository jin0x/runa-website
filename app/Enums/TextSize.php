<?php

namespace App\Enums;

class TextSize
{
    const XLARGE = 'text-xlarge';
    const LARGE = 'text-large';
    const MEDIUM = 'text-medium';
    const BASE = 'text-base';  // Also known as text-default
    const SMALL = 'text-small';
    const XSMALL = 'text-xsmall';
    const CAPS = 'text-caps';

    public static function getValues(): array {
        return [
            self::XLARGE,
            self::LARGE,
            self::MEDIUM,
            self::BASE,
            self::SMALL,
            self::XSMALL,
            self::CAPS,
        ];
    }
}
