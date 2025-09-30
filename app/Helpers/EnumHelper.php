<?php

namespace App\Helpers;

use App\Enums\ThemeVariant;
use App\Enums\SectionSize;

class EnumHelper
{
    /**
     * Convert theme string to ThemeVariant enum
     */
    public static function getThemeVariant(string $theme): ThemeVariant
    {
        return match ($theme) {
            'light' => ThemeVariant::LIGHT,
            'dark' => ThemeVariant::DARK,
            'green' => ThemeVariant::GREEN,
            'purple' => ThemeVariant::PURPLE,
            'default' => ThemeVariant::LIGHT,
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
