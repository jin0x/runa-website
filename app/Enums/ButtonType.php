<?php

namespace App\Enums;

class ButtonType {
    const BUTTON = 'button';
    const LINK   = 'a';

    public static function getValues(): array {
        return [
            self::BUTTON,
            self::LINK,
        ];
    }
}
