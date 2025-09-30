<?php

namespace App\Blocks;

use App\Fields\Partials\SectionHeading;
use App\Fields\Partials\MediaComponent;
use App\Fields\Partials\SectionOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class ContentMediaRepeater extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Content Media Repeater';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Flexible content & media block with repeater support.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'runa';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = 'editor-ul';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = [];

    /**
     * The block post type allow list.
     *
     * @var array
     */
    public $post_types = [];

    /**
     * The parent block type allow list.
     *
     * @var array
     */
    public $parent = [];

    /**
     * The ancestor block type allow list.
     *
     * @var array
     */
    public $ancestor = [];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'preview';

    /**
     * The default block alignment.
     *
     * @var string
     */
    public $align = '';

    /**
     * The default block text alignment.
     *
     * @var string
     */
    public $align_text = '';

    /**
     * The default block content alignment.
     *
     * @var string
     */
    public $align_content = '';

    /**
     * The default block spacing.
     *
     * @var array
     */
    public $spacing = [
        'padding' => null,
        'margin' => null,
    ];

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => true,
        'align_text' => false,
        'align_content' => false,
        'full_height' => false,
        'anchor' => false,
        'mode' => true,
        'multiple' => true,
        'jsx' => true,
        'color' => [
            'background' => false,
            'text' => false,
            'gradients' => false,
        ],
        'spacing' => [
            'padding' => false,
            'margin' => false,
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        return [
            // Section Heading Fields
            'section_eyebrow' => $this->getSectionEyebrow(),
            'section_title' => $this->getSectionTitle(),
            'section_description' => $this->getSectionDescription(),

            // Content Fields (repeater)
            'items' => $this->getContentItems(),

            // Section Options
            'section_size' => $this->getSectionSize(),
            'theme' => $this->getTheme(),
        ];
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $contentMediaRepeater = Builder::make('content_media_repeater');

        $contentMediaRepeater
            ->addMessage('Content Media Repeater', 'Flexible content & media block with repeater support.')

            ->addTab('Section Heading', [
                'placement' => 'top',
            ])
            ->addPartial(SectionHeading::class)

            ->addTab('Content', [
                'placement' => 'top',
            ])
                ->addRepeater('items', [
                        'label' => 'Content Items',
                        'layout' => 'block',
                        'button_label' => 'Add Item',
                    ])
                    ->addText('content_eyebrow', [
                        'label' => 'Eyebrow',
                        'instructions' => 'Small text displayed above the heading',
                    ])
                    ->addWysiwyg('content_text', [
                        'label' => 'Text Content',
                        'instructions' => 'Main body text for the content section',
                        'required' => 0,
                        'tabs' => 'visual',
                        'toolbar' => 'Full',
                        'media_upload' => 0,
                    ])
                    ->addRepeater('ctas', [
                        'label' => 'Call to Actions',
                        'instructions' => 'Add one or more call to action buttons',
                        'min' => 0,
                        'max' => 2,
                        'layout' => 'block',
                        'button_label' => 'Add Call to Action',
                    ])
                    ->addLink('cta', [
                        'label' => 'Button',
                        'return_format' => 'array',
                    ])
                    ->endRepeater()
                    ->addTrueFalse('reverse_layout', [
                        'label' => 'Reverse Layout',
                        'instructions' => 'Switch the default position of text and media (desktop only)',
                        'default_value' => 0,
                        'ui' => 1,
                    ])
                    ->addPartial(MediaComponent::class)
                ->endRepeater()
            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::class);
        return $contentMediaRepeater->build();
    }


    /**
     * Section heading getters
     */
    public function getSectionEyebrow()
    {
        return get_field('eyebrow');
    }

    public function getSectionTitle()
    {
        return get_field('heading');
    }

    public function getSectionDescription()
    {
        return get_field('subtitle');
    }

    /**
     * Section content getters
     */

    public function getContentItems()
    {
        return get_field('items') ?: [];
    }

    /**
     * Section options getters
     */
    public function getSectionSize()
    {
        return get_field('section_size') ?: 'md';
    }

    public function getTheme()
    {
        return get_field('theme') ?: 'light';
    }
}
