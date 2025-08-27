<?php

namespace App\Enums;

class ButtonSize {
    const DEFAULT = 'default';
    const SMALL   = 'small';

    public static function getValues(): array {
        return [
            self::DEFAULT,
            self::SMALL,
        ];
    }
}
