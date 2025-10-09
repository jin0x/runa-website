<?php

namespace App\Enums;

class ButtonSize {
    const DEFAULT = 'default';
    const SMALL   = 'small';
    const LARGE   = 'large';

    public static function getValues(): array {
        return [
            self::DEFAULT,
            self::SMALL,
            self::LARGE,
        ];
    }
}
