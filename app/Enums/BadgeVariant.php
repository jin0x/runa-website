<?php

namespace App\Enums;

class BadgeVariant
{
    const PRIMARY = 'primary';
    const SECONDARY = 'secondary';
    const TERTIARY = 'tertiary';

    public static function getValues(): array {
        return [
            self::PRIMARY,
            self::SECONDARY,
            self::TERTIARY,
        ];
    }
}
