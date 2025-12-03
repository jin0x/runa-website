<?php

namespace App\Options;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Options as Field;

class ResourcesSettings extends Field
{
    /**
     * The option page menu name.
     *
     * @var string
     */
    public $name = 'Resources Settings';

    /**
     * The option page document title.
     *
     * @var string
     */
    public $title = 'Resources Settings | Theme Options';

    /**
     * The option page field group.
     *
     * @return array
     */
    public function fields()
    {
        $resourcesSettings = Builder::make('resources_settings');

        $resourcesSettings
            ->addTab('General', [
                'placement' => 'left',
            ])
            ->addImage('resources_background_image', [
                'label' => 'Resources Background Image',
                'instructions' => 'Background image for the resources page header section',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ]);

        return $resourcesSettings->build();
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