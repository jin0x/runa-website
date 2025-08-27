<?php

namespace App\View\Composers;

class MobileNavigation extends Navigation {
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.mobile-navigation',
    ];
}
