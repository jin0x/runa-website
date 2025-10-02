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

class IconCards extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Icon Cards';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A grid of cards with icons, titles, and descriptions for showcasing features or key content.';

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
            'cards' => $this->getContentCards(),

            // Section Options
            'section_size' => $this->getSectionSize(),
            'theme' => $this->getTheme(),
            'columns' => $this->getColumns(),
            'cards_background' => $this->getCardsBackground(),
        ];
    }

    /**
    * The block field group.
    */
    public function fields(): array
    {
        $iconCards = Builder::make('icon_cards');

        $iconCards
            ->addMessage('Icon Cards', 'A grid of cards with icons, titles, and descriptions for showcasing features or key content.')

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
            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::withConfig([
                'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK]
            ]))
            ->addSelect('columns', [
                'label' => 'Number of Columns',
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
            ])
            ->addSelect('cards_background', [
                'label' => 'Cards Background',
                'instructions' => 'Choose the color background for the cards',
                'choices' => [
                    'cyan' => 'Hyper Cyan',
                    'green' => 'Green',
                ],
                'default_value' => 'cyan',
                'wrapper' => [
                    'width' => '50',
                ],
            ]);
        return $iconCards->build();
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

    public function getCardsBackground()
    {
        return get_field('cards_background') ?: 'cyan';
    }
}
