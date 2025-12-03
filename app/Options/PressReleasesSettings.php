<?php

namespace App\Options;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Options as Field;

class PressReleasesSettings extends Field
{
    /**
     * The option page menu name.
     *
     * @var string
     */
    public $name = 'Press Releases Settings';

    /**
     * The option page document title.
     *
     * @var string
     */
    public $title = 'Press Releases Settings | Theme Options';

    /**
     * The option page field group.
     *
     * @return array
     */
    public function fields()
    {
        $pressReleasesSettings = Builder::make('press_releases_settings');

        $pressReleasesSettings
            ->addTab('General', [
                'placement' => 'left',
            ])
            ->addImage('press_releases_background_image', [
                'label' => 'Press Releases Background Image',
                'instructions' => 'Background image for the press releases page header section',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ]);

        return $pressReleasesSettings->build();
    }

    /**
     * The slug of another WP admin page.
     *
     * @var string
     */
    public $parent = 'theme-settings';

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