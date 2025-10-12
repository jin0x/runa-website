<?php

namespace App\Enums;

class HeadingSize
{
    const H1 = 'heading-1';
    const H2 = 'heading-2';
    const H3 = 'heading-3';
    const H3_BOLD = 'heading-3-bold';
    const H4 = 'heading-4';
    const H4_BOLD = 'heading-4-bold';
    const H5 = 'heading-5';
    const H5_BOLD = 'heading-5-bold';
    const H6 = 'heading-6';
    const H6_BOLD = 'heading-6-bold';
    const H7 = 'heading-7';
    const H7_BOLD = 'heading-7-bold';
    const HERO = 'heading-hero';
    const HERO_MEDIUM = 'heading-hero-medium';
    const SUPER = 'heading-super';
    const SUPER_DUPER = 'heading-super-duper';
    const DISPLAY_LARGE = 'text-display-large';
    const DISPLAY_MEDIUM = 'text-display-medium';
    const DISPLAY_SMALL = 'text-display-small';

    public static function getValues(): array {
        return [
            self::H1,
            self::H2,
            self::H3,
            self::H3_BOLD,
            self::H4,
            self::H4_BOLD,
            self::H5,
            self::H5_BOLD,
            self::H6,
            self::H6_BOLD,
            self::H7,
            self::H7_BOLD,
            self::HERO,
            self::HERO_MEDIUM,
            self::SUPER,
            self::SUPER_DUPER,
            self::DISPLAY_LARGE,
            self::DISPLAY_MEDIUM,
            self::DISPLAY_SMALL,
        ];
    }
}
