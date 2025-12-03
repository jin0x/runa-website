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
            ])
            ->addRange('press_releases_background_opacity', [
                'label' => 'Background Overlay Opacity',
                'instructions' => 'Controls the darkness of the overlay on top of the background image (higher = darker overlay, better text readability)',
                'default_value' => 60,
                'min' => 0,
                'max' => 90,
                'step' => 5,
                'prepend' => '',
                'append' => '%',
            ])
            ->addSelect('press_releases_background_position', [
                'label' => 'Background Image Position',
                'instructions' => 'Controls how the background image is positioned within the hero section',
                'choices' => [
                    'center top' => 'Top',
                    'center 25%' => 'Upper Center',
                    'center center' => 'Center',
                    'center 75%' => 'Lower Center', 
                    'center bottom' => 'Bottom',
                    'left center' => 'Left',
                    'right center' => 'Right',
                    'left top' => 'Top Left',
                    'right top' => 'Top Right',
                    'left bottom' => 'Bottom Left',
                    'right bottom' => 'Bottom Right',
                ],
                'default_value' => 'center center',
                'allow_null' => 0,
                'ui' => 1,
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