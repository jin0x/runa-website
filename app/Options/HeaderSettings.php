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
            ->addImage('header_logo_secondary', [
                'label' => 'Header Logo (Secondary / Dark)',
                'instructions' => 'Upload the secondary/dark logo version for layouts without a hero (e.g., blog posts, case studies).',
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
            ])

            ->addTab('Header Menu', [
                'placement' => 'left',
            ])
            ->addTrueFalse('enable_mega_menu', [
                'label' => 'Enable Mega Menu',
                'instructions' => 'When enabled, uses custom mega menu structure. When disabled, falls back to WordPress menu system.',
                'default_value' => 0,
                'ui' => 1,
            ])
            ->addRepeater('header_menu_items', [
                'label' => 'Menu Items',
                'instructions' => 'Add your top-level menu items. Each can have submenu groups and callouts.',
                'min' => 0,
                'max' => 10,
                'layout' => 'block',
                'button_label' => 'Add Menu Item',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'enable_mega_menu',
                            'operator' => '==',
                            'value' => 1,
                        ]
                    ]
                ]
            ])
                ->addLink('menu_item_link', [
                    'label' => 'Menu Item',
                    'instructions' => 'The main navigation link',
                    'required' => 1,
                    'return_format' => 'array',
                ])
                ->addTrueFalse('has_submenu', [
                    'label' => 'Has Submenu',
                    'instructions' => 'Enable to add submenu groups (mega menu dropdown)',
                    'default_value' => 0,
                    'ui' => 1,
                ])

                // Submenu Groups
                ->addRepeater('submenu_groups', [
                    'label' => 'Submenu Groups',
                    'instructions' => 'Add up to 2 submenu groups (columns). Choose between regular link groups or featured content.',
                    'min' => 0,
                    'max' => 2,
                    'layout' => 'block',
                    'button_label' => 'Add Submenu Group',
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'has_submenu',
                                'operator' => '==',
                                'value' => 1,
                            ]
                        ]
                    ]
                ])
                    ->addRadio('group_type', [
                        'label' => 'Group Type',
                        'instructions' => 'Choose the type of content for this group',
                        'required' => 1,
                        'choices' => [
                            'regular' => 'Regular Links',
                            'featured' => 'Featured Content',
                        ],
                        'default_value' => 'regular',
                        'layout' => 'horizontal',
                    ])

                    // Regular Group Fields
                    ->addText('group_title', [
                        'label' => 'Group Title',
                        'instructions' => 'Title for this submenu section (e.g., "Platform", "Product")',
                        'maxlength' => 50,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'group_type',
                                    'operator' => '==',
                                    'value' => 'regular',
                                ]
                            ]
                        ]
                    ])
                    ->addImage('group_icon', [
                        'label' => 'Group Icon',
                        'instructions' => 'Upload an icon for this group (SVG or PNG recommended)',
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'group_type',
                                    'operator' => '==',
                                    'value' => 'regular',
                                ]
                            ]
                        ]
                    ])
                    ->addRepeater('submenu_items', [
                        'label' => 'Submenu Items',
                        'instructions' => 'Add links that appear under this group',
                        'min' => 0,
                        'max' => 10,
                        'layout' => 'table',
                        'button_label' => 'Add Link',
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'group_type',
                                    'operator' => '==',
                                    'value' => 'regular',
                                ]
                            ]
                        ]
                    ])
                        ->addLink('link', [
                            'label' => 'Link',
                            'required' => 1,
                            'return_format' => 'array',
                        ])
                        ->addText('description', [
                            'label' => 'Description',
                            'instructions' => 'Optional short description',
                            'maxlength' => 150,
                        ])
                    ->endRepeater()

                    // Featured Group Fields
                    ->addText('featured_title', [
                        'label' => 'Featured Title',
                        'instructions' => 'Main heading for the featured content',
                        'maxlength' => 100,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'group_type',
                                    'operator' => '==',
                                    'value' => 'featured',
                                ]
                            ]
                        ]
                    ])
                    ->addTextarea('featured_description', [
                        'label' => 'Featured Description',
                        'instructions' => 'Description text for the featured content',
                        'rows' => 3,
                        'maxlength' => 250,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'group_type',
                                    'operator' => '==',
                                    'value' => 'featured',
                                ]
                            ]
                        ]
                    ])
                    ->addImage('featured_background', [
                        'label' => 'Featured Background Image',
                        'instructions' => 'Background image for the featured panel',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'group_type',
                                    'operator' => '==',
                                    'value' => 'featured',
                                ]
                            ]
                        ]
                    ])
                    ->addLink('featured_cta', [
                        'label' => 'Featured CTA Button',
                        'instructions' => 'Call-to-action button',
                        'return_format' => 'array',
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'group_type',
                                    'operator' => '==',
                                    'value' => 'featured',
                                ]
                            ]
                        ]
                    ])
                ->endRepeater() // End submenu_groups

                // Callout Section
                ->addGroup('callout', [
                    'label' => 'Callout Section',
                    'instructions' => 'Optional callout banner at the bottom of the mega menu',
                    'layout' => 'block',
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'has_submenu',
                                'operator' => '==',
                                'value' => 1,
                            ]
                        ]
                    ]
                ])
                    ->addTrueFalse('enable_callout', [
                        'label' => 'Enable Callout',
                        'default_value' => 0,
                        'ui' => 1,
                    ])
                    ->addText('callout_title', [
                        'label' => 'Callout Title',
                        'maxlength' => 50,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'enable_callout',
                                    'operator' => '==',
                                    'value' => 1,
                                ]
                            ]
                        ]
                    ])
                    ->addTextarea('callout_description', [
                        'label' => 'Callout Description',
                        'rows' => 2,
                        'maxlength' => 250,
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'enable_callout',
                                    'operator' => '==',
                                    'value' => 1,
                                ]
                            ]
                        ]
                    ])
                    ->addImage('callout_background', [
                        'label' => 'Callout Background Image',
                        'instructions' => 'Optional background image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'enable_callout',
                                    'operator' => '==',
                                    'value' => 1,
                                ]
                            ]
                        ]
                    ])
                    ->addLink('callout_cta', [
                        'label' => 'Callout CTA Button',
                        'return_format' => 'array',
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'enable_callout',
                                    'operator' => '==',
                                    'value' => 1,
                                ]
                            ]
                        ]
                    ])
                ->endGroup() // End callout

            ->endRepeater(); // End header_menu_items

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
