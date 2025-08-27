<?php

namespace App\Blocks;

use App\Fields\Partials\SectionHeading;
use App\Fields\Partials\SectionOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class CardsBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Cards';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A block displaying a grid of cards.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'formatting';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = 'grid-view';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['cards', 'grid', 'features'];

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
    public $align = 'full';

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
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => true,
        'align_text' => false,
        'align_content' => false,
        'full_height' => false,
        'anchor' => true,
        'mode' => true,
        'multiple' => true,
        'jsx' => true,
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            // Section Heading Fields
            'section_eyebrow' => $this->getSectionEyebrow(),
            'section_title' => $this->getSectionTitle(),
            'section_description' => $this->getSectionDescription(),

            // Cards
            'cards' => $this->getCards(),
            'columns' => $this->getColumns(),

            // Section Options
            'section_size' => $this->getSectionSize(),
            'theme' => $this->getTheme(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $cardsBlock = Builder::make('cards');

        $cardsBlock
            ->addTab('Section Heading', [
                'placement' => 'top',
            ])
            ->addPartial(SectionHeading::class)

            ->addTab('Cards', [
                'placement' => 'top',
            ])
            ->addSelect('columns', [
                'label' => 'Number of Columns',
                'instructions' => 'Select the number of columns to display',
                'choices' => [
                    '2' => '2 Columns',
                    '3' => '3 Columns',
                ],
                'default_value' => '3',
                'required' => 1,
            ])
            ->addRepeater('cards', [
                'label' => 'Cards',
                'instructions' => 'Add cards to the grid',
                'min' => 1,
                'layout' => 'block',
                'button_label' => 'Add Card',
            ])
            ->addText('eyebrow', [
                'label' => 'Eyebrow',
                'instructions' => 'Small text displayed above the title',
            ])
            ->addText('title', [
                'label' => 'Title',
                'instructions' => 'Card title',
                'required' => 1,
            ])
            ->addTextarea('excerpt', [
                'label' => 'Excerpt',
                'instructions' => 'Brief description or excerpt',
                'rows' => 3,
            ])
            ->addImage('image', [
                'label' => 'Image',
                'instructions' => 'Card image',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ])
            ->addLink('cta', [
                'label' => 'Call to Action',
                'instructions' => 'Link for the card',
                'return_format' => 'array',
                'required' => 1,
            ])
            ->endRepeater()

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::class);

        return $cardsBlock->build();
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
     * Cards getters
     */
    public function getCards()
    {
        $cards = get_field('cards') ?: [];
        $processed_cards = [];

        foreach ($cards as $card) {
            $processed_card = $card;

            // Process image field
            if (!empty($card['image']) && is_array($card['image'])) {
                $processed_card['image'] = $card['image']['url'];
            }

            $processed_cards[] = $processed_card;
        }

        return $processed_cards;
    }

    public function getColumns()
    {
        return get_field('columns') ?: '3';
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
