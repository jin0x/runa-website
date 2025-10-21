<?php

namespace App\Enums;

class TextSize
{
    const CAPTION = 'text-caption';
    const CAPTION_BOLD = 'text-caption-bold';
    const XSMALL = 'text-xsmall';
    const XSMALL_BOLD = 'text-xsmall-bold';
    const SMALL = 'text-small';
    const SMALL_BOLD = 'text-small-bold';
    const BASE = 'text-default';
    const BASE_BOLD = 'text-default-bold';
    const MEDIUM = 'text-medium';
    const MEDIUM_BOLD = 'text-medium-bold';
    const LARGE = 'text-large';
    const LARGE_BOLD = 'text-large-bold';
    const XLARGE = 'text-xlarge';
    const XLARGE_BOLD = 'text-xlarge-bold';
    const CAPS = 'text-caps';
    const CAPS_BOLD = 'text-caps-bold';
    const EYEBROW = 'text-eyebrow';
    const EYEBROW_BOLD = 'text-eyebrow-bold';

    public static function getValues(): array {
        return [
            self::CAPTION,
            self::CAPTION_BOLD,
            self::XSMALL,
            self::XSMALL_BOLD,
            self::SMALL,
            self::SMALL_BOLD,
            self::BASE,
            self::BASE_BOLD,
            self::MEDIUM,
            self::MEDIUM_BOLD,
            self::LARGE,
            self::LARGE_BOLD,
            self::XLARGE,
            self::XLARGE_BOLD,
            self::CAPS,
            self::CAPS_BOLD,
            self::EYEBROW,
            self::EYEBROW_BOLD,
        ];
    }
}
