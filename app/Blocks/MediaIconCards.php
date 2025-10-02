<?php

namespace App\Blocks;

use App\Enums\ThemeVariant;
use App\Fields\Partials\SectionHeading;
use App\Fields\Partials\MediaComponent;
use App\Fields\Partials\SectionOptions;
use App\Fields\Partials\GridOptions;
use App\Fields\Partials\CTALink;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class MediaIconCards extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Media Icon Cards';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A section with a top media element, followed by a grid of icon cards. Great for showcasing features, stats, or benefits.';

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

            // Media Fields
            'media_type' => $this->getMediaType(),
            'image' => $this->getImage(),
            'video' => $this->getVideo(),
            'lottie' => $this->getLottie(),

            // Content Fields (repeater)
            'cards' => $this->getContentCards(),

            // Section Options
            'section_size' => $this->getSectionSize(),
            'theme' => $this->getTheme(),
            'columns' => $this->getColumns(),
        ];
    }

    /**
    * The block field group.
    */
    public function fields(): array
    {
        $mediaIconCards = Builder::make('media_icon_cards');

        $mediaIconCards
            ->addMessage('Media Icon Cards', 'A section with a top media element, followed by a grid of icon cards. Great for showcasing features, stats, or benefits.')

            ->addTab('Section Heading', [
                'placement' => 'top',
            ])
            ->addPartial(SectionHeading::class)

            ->addTab('Content', [
                'placement' => 'top',
            ])
                ->addRepeater('cards', [
                        'label' => 'Content Cards',
                        'layout' => 'block',
                        'button_label' => 'Add Card',
                    ])
                    ->addText('title', [
                        'label' => 'Title',
                        'instructions' => 'Card title',
                    ])
                    ->addTextarea('text', [
                        'label' => 'Description',
                        'instructions' => 'Brief description or excerpt',
                        'rows' => 3,
                    ])
                    ->addImage('icon', [
                        'label' => 'Icon',
                        'instructions' => 'Card icon',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                    ])
                ->endRepeater()
            ->addTab('Media', [
                'placement' => 'top',
            ])
                ->addPartial(MediaComponent::class)
            ->addTab('Settings', [
                'placement' => 'top',
            ])
                ->addPartial(SectionOptions::withConfig([
                    'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK, ThemeVariant::PURPLE]
                ]))
                ->addSelect('columns', [
                    'label' => 'Number of Columns for cards',
                    'instructions' => 'Select the number of columns to display',
                    'choices' => [
                        '2' => '2',
                        '3' => '3',
                    ],
                    'default_value' => '3',
                    'required' => 1,
                    'wrapper' => [
                        'width' => '50',
                    ],
                ]);
        return $mediaIconCards->build();
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

    public function getContentCards()
    {
        return get_field('cards') ?: [];
    }

    /**
     * Media getters
     */
    public function getMediaType()
    {
        return get_field('media_type') ?: 'image';
    }

    public function getImage()
    {
        return get_field('image');
    }

    public function getVideo()
    {
        return get_field('video');
    }

    public function getLottie()
    {
        return get_field('lottie');
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

    public function getColumns()
    {
        return get_field('columns') ?: '3';
    }
}
