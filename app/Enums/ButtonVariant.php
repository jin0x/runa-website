<?php

namespace App\Enums;

class ButtonVariant
{
    const PRIMARY = 'primary';
    const SECONDARY = 'secondary';

    public static function getValues(): array {
        return [
            self::PRIMARY,
            self::SECONDARY,
        ];
    }
}
