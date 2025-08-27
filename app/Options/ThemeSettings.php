<?php

namespace App\Options;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Options as Field;

class ThemeSettings extends Field
{
    /**
     * The option page menu name.
     *
     * @var string
     */
    public $name = 'Theme Settings';

    /**
     * The option page document title.
     *
     * @var string
     */
    public $title = 'Theme Settings | Options';

    /**
     * The option page field group.
     *
     * @return array
     */
    public function fields()
    {
        $themeSettings = Builder::make('theme_settings');

        $themeSettings
            ->addMessage('theme_settings_message', [
                'label' => 'Theme Settings',
                'message' => 'Use the submenu to access specific theme settings areas.',
                'new_lines' => 'wpautop',
                'esc_html' => 0,
            ]);

        return $themeSettings->build();
    }

    /**
     * The option page menu position.
     *
     * @var int
     */
    public $position = 80;

    /**
     * The slug of another WP admin page.
     *
     * @var string
     */
    public $parent = null;

    /**
     * The option page menu icon.
     *
     * @var string
     */
    public $icon = 'dashicons-admin-customizer';

    /**
     * Redirect to the first child page if one exists.
     *
     * @var bool
     */
    public $redirect = true;

    /**
     * The post ID to save and load values from.
     *
     * @var string
     */
    public $post = 'options';

    /**
     * The option page autoload setting.
     *
     * @var bool
     */
    public $autoload = true;
}
