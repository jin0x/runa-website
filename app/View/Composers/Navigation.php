<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use Log1x\Navi\Navi;

class Navigation extends Composer {
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.navigation',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with() {
        $data = $this->view->getData();
        $menu_location = $data['menu'] ?? 'primary_navigation';

        return [
            'navigation' => $this->navigation($menu_location),
        ];
    }

    /**
     * Returns the navigation for the specified menu location.
     *
     * @param string $menu_location
     * @return array|null
     */
    public function navigation($menu_location = 'primary_navigation') {
        $menu = Navi::make()->build($menu_location);

        if ($menu->isEmpty()) {
            return null;
        }

        return $menu->all();
    }
}
