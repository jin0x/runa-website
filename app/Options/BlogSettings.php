<?php

namespace App\Options;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Options as Field;

class BlogSettings extends Field
{
    /**
     * The option page menu name.
     *
     * @var string
     */
    public $name = 'Blog Settings';

    /**
     * The option page document title.
     *
     * @var string
     */
    public $title = 'Blog Settings | Theme Options';

    /**
     * The option page field group.
     *
     * @return array
     */
    public function fields()
    {
        $blogSettings = Builder::make('blog_settings');

        $blogSettings
            ->addTab('General', [
                'placement' => 'left',
            ])
            ->addImage('blog_background_image', [
                'label' => 'Blog Background Image',
                'instructions' => 'Background image for the blog page header section',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ]);

        return $blogSettings->build();
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