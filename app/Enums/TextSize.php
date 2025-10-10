<?php

namespace App\Enums;

class TextSize
{
    const CAPTION = 'text-caption';
    const XSMALL = 'text-xsmall';
    const SMALL = 'text-small';
    const BASE = 'text-default';
    const MEDIUM = 'text-medium';
    const LARGE = 'text-large';
    const XLARGE = 'text-xlarge';
    const CAPS = 'text-caps';
    const EYEBROW = 'text-eyebrow';

    public static function getValues(): array {
        return [
            self::CAPTION,
            self::XSMALL,
            self::SMALL,
            self::BASE,
            self::MEDIUM,
            self::LARGE,
            self::XLARGE,
            self::CAPS,
            self::EYEBROW,
        ];
    }
}
