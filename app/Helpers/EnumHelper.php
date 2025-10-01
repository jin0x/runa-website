<?php

namespace App\Helpers;

use App\Enums\ThemeVariant;
use App\Enums\SectionSize;

class EnumHelper
{
    /**
     * Convert theme string to ThemeVariant constant
     */
    public static function getThemeVariant(string $theme): string
    {
        return match ($theme) {
            'light' => ThemeVariant::LIGHT,
            'dark' => ThemeVariant::DARK,
            'green' => ThemeVariant::GREEN,
            'purple' => ThemeVariant::PURPLE,
            default => ThemeVariant::LIGHT,
        };
    }

    /**
     * Convert theme variant to optimal section heading variant for contrast
     */
    public static function getSectionHeadingVariant(string $themeVariant): string
    {
        return match ($themeVariant) {
            ThemeVariant::LIGHT => ThemeVariant::LIGHT,   // dark text on light bg
            ThemeVariant::GREEN => ThemeVariant::LIGHT,   // dark text on green bg
            ThemeVariant::PURPLE => ThemeVariant::LIGHT,  // dark text on purple bg
            ThemeVariant::DARK => ThemeVariant::GREEN,    // colorful text on dark bg
            default => ThemeVariant::LIGHT,
        };
    }

    /**
     * Convert section size string to SectionSize enum
     */
    public static function getSectionSize(string $size): SectionSize
    {
        return match ($size) {
            'none' => SectionSize::NONE,
            'xs' => SectionSize::XSMALL,
            'sm' => SectionSize::SMALL,
            'md' => SectionSize::MEDIUM,
            'lg' => SectionSize::LARGE,
            'xl' => SectionSize::XLARGE,
            default => SectionSize::MEDIUM,
        };
    }
}
