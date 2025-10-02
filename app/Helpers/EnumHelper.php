<?php

namespace App\Helpers;

use App\Enums\ThemeVariant;
use App\Enums\SectionSize;
use App\Enums\SectionHeadingVariant;

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
            'cyan' => ThemeVariant::CYAN,
            'yellow' => ThemeVariant::YELLOW,
            default => ThemeVariant::LIGHT,
        };
    }

    /**
     * Convert theme variant to optimal section heading variant for contrast
     */
    public static function getSectionHeadingVariant(string $themeVariant): string
    {
        return match ($themeVariant) {
            ThemeVariant::LIGHT => SectionHeadingVariant::LIGHT,   // dark text on light bg
            ThemeVariant::GREEN => SectionHeadingVariant::LIGHT,   // dark text on green bg
            ThemeVariant::PURPLE => SectionHeadingVariant::LIGHT,  // dark text on purple bg
            ThemeVariant::DARK => SectionHeadingVariant::GREEN,    // colorful text on dark bg
            ThemeVariant::CYAN => SectionHeadingVariant::LIGHT,    // dark text on cyan bg
            ThemeVariant::YELLOW => SectionHeadingVariant::LIGHT,  // dark text on yellow bg
            default => SectionHeadingVariant::LIGHT,
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

    /**
     * Get background CSS class for card color (using ThemeVariant)
     */
    public static function getCardBackgroundClass(string $themeVariant): string
    {
        return match ($themeVariant) {
            ThemeVariant::PURPLE => 'bg-secondary-purple',
            ThemeVariant::CYAN => 'bg-secondary-cyan',
            ThemeVariant::YELLOW => 'bg-primary-yellow',
            ThemeVariant::GREEN => 'bg-primary-green-soft',
            default => 'bg-secondary-cyan',
        };
    }
}
