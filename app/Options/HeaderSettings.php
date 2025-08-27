<?php

namespace App\Options;

use App\Fields\Partials\CTALink;
use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Options as Field;

class HeaderSettings extends Field
{
    /**
     * The option page menu name.
     *
     * @var string
     */
    public $name = 'Header Settings';

    /**
     * The option page document title.
     *
     * @var string
     */
    public $title = 'Header Settings | Theme Options';

    /**
     * The option page field group.
     *
     * @return array
     */
    public function fields()
    {
        $headerSettings = Builder::make('header_settings');

        $headerSettings
            ->addTab('General', [
                'placement' => 'left',
            ])
            ->addImage('header_logo', [
                'label' => 'Header Logo',
                'instructions' => 'Upload your site logo',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ])
            ->addRepeater('header_buttons', [
                'label' => 'Header Buttons',
                'instructions' => 'Add up to 2 call-to-action buttons. The first button will be primary, the second will be secondary.',
                'min' => 0,
                'max' => 2,
                'layout' => 'block',
                'button_label' => 'Add Button',
            ])
            ->addLink('cta', [
                'label' => 'Button',
                'return_format' => 'array',
            ])
            ->endRepeater()

            ->addTab('Header banner', [
                'placement' => 'left',
            ])
            ->addTrueFalse('enable_header_banner', [
                'label' => 'Enable header banner',
                'default_value' => 0,
                'ui' => 1,
            ])
            ->addWysiwyg('header_banner_message', [
                'label' => 'Header banner message',
                'instructions' => 'Content for the banner shown at the top of the site',
                'tabs' => 'visual',
                'toolbar' => 'basic',
                'media_upload' => 0,
                'conditional_logic' => [
                    [
                        [
                            'field' => 'enable_header_banner',
                            'operator' => '==',
                            'value' => 1,
                        ]
                    ]
                ]
            ]);

        return $headerSettings->build();
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
