<?php

namespace App\Options;

use App\Fields\Partials\SocialNetworks;
use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Options as Field;

class FooterSettings extends Field
{
    /**
     * The option page menu name.
     *
     * @var string
     */
    public $name = 'Footer Settings';

    /**
     * The option page document title.
     *
     * @var string
     */
    public $title = 'Footer Settings | Theme Options';

    /**
     * The option page field group.
     *
     * @return array
     */
    public function fields()
    {
        $footerSettings = Builder::make('footer_settings');

        $footerSettings
            ->addTab('General', [
                'placement' => 'left',
            ])
            ->addImage('footer_logo', [
                'label' => 'Footer Logo',
                'instructions' => 'Upload your site footer logo',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ])
            ->addRepeater('footer_buttons', [
                'label' => 'Footer Buttons',
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
            ->addTextarea('copyrights', [
                'label' => 'Copyrights',
                'instructions' => 'Copyright text for the footer',
                'rows' => 4,
                'new_lines' => 'br',
                'default_value' => 'Copyright &copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.',
            ])
            ->addPartial(SocialNetworks::class);

        return $footerSettings->build();
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
