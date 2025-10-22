<?php

namespace App\Blocks;

use App\Enums\ThemeVariant;
use App\Fields\Partials\CardOptions;
use App\Fields\Partials\SectionHeading;
use App\Fields\Partials\SectionOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class ImageCardsHover extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Image Cards Hover';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Grid of image and text items with hover reveal for descriptions.';

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
    public $post_types = ['page'];

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
            'cards' => $this->getContentCards(),

            // Card Options
            'card_color' => $this->getCardColor(),

            // Section Options
            'section_size' => $this->getSectionSize(),
            'theme' => $this->getTheme(),
            'arch_position' => $this->getArchPosition(),
        ];
    }

    /**
    * The block field group.
    */
    public function fields(): array
    {
        $ImageCardsHover = Builder::make('image_cards_hover');

        $ImageCardsHover
            ->addMessage('Image Cards Hover', 'Grid of image and text items with hover reveal for descriptions.')

            ->addTab('Section Heading', [
                'placement' => 'top',
            ])
            ->addPartial(SectionHeading::class)

            ->addTab('Content', [
                'placement' => 'top',
            ])
                ->addRepeater('cards', [
                        'label' => 'Image Hover Cards',
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
                    ->addImage('image', [
                        'label' => 'Image',
                        'instructions' => 'Card Image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                    ])
                ->endRepeater()

            ->addTab('Display Options', [
                'placement' => 'top',
            ])
            ->addPartial(CardOptions::withConfig([
                'colors' => [ThemeVariant::PURPLE, ThemeVariant::CYAN, ThemeVariant::YELLOW, ThemeVariant::GREEN]
            ]))

            ->addTab('Settings', [
                'placement' => 'top',
            ])
                ->addPartial(SectionOptions::withConfig([
                'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK]
            ]));
        return $ImageCardsHover->build();
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
     * Get the card color field.
     *
     * @return string
     */
    public function getCardColor()
    {
        return get_field('card_color') ?: ThemeVariant::CYAN;
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

    public function getArchPosition()
    {
        return get_field('arch_position') ?: 'none';
    }
}
