<?php

namespace App\Enums;

class SectionHeadingVariant
{
    public const LIGHT = 'light';                        // Dark text on light backgrounds
    public const GREEN = 'green';                        // Colorful text on dark backgrounds
    public const PURPLE = 'purple';                      // Purple-themed text
    public const MIXED_GREEN_LIGHT = 'mixed-green-light'; // Green eyebrow + light title/subtitle

    public static function getValues(): array
    {
        return [
            self::LIGHT,
            self::GREEN,
            self::PURPLE,
            self::MIXED_GREEN_LIGHT,
        ];
    }
}