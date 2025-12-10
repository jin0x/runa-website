<?php

namespace App\Blocks;

use App\Enums\ThemeVariant;
use App\Fields\Partials\CardOptions;
use App\Fields\Partials\SectionOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class FeatureCardsBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Feature Cards';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A block displaying feature cards with images, titles, descriptions, and CTAs.';

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
    public $icon = 'grid-view';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['feature', 'cards', 'content', 'grid'];

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
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => true,
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
            'heading' => $this->getHeading(),
            'cards' => $this->getCards(),
            'card_color' => $this->getCardColor(),
            'card_size' => $this->getCardSize(),
            'columns' => $this->getColumns(),
            'section_size' => $this->getSectionSize(),
            'theme' => $this->getTheme(),
            'image_ratio' => $this->getImageRatio(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $featureCards = Builder::make('feature_cards');

        $featureCards
            ->addTab('Content', [
                'placement' => 'top',
            ])
            ->addText('heading', [
                'label' => 'Section Heading',
                'instructions' => 'Main heading for the feature cards section',
                'required' => 1,
            ])
            ->addRepeater('cards', [
                'label' => 'Feature Cards',
                'instructions' => 'Add feature cards',
                'min' => 1,
                'max' => 6,
                'layout' => 'block',
                'button_label' => 'Add Feature Card',
            ])
            ->addImage('image', [
                'label' => 'Card Image',
                'instructions' => 'Upload an image for this card',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'required' => 1,
            ])
            ->addText('title', [
                'label' => 'Card Title',
                'instructions' => 'Title for this feature card',
                'required' => 1,
            ])
            ->addTextarea('description', [
                'label' => 'Description (Optional)',
                'instructions' => 'Optional description text for the card',
                'rows' => 3,
                'required' => 0,
            ])
            ->addLink('cta', [
                'label' => 'Call to Action',
                'instructions' => 'Link for this card',
                'return_format' => 'array',
                'required' => 1,
            ])
            
            ->endRepeater()

            ->addTab('Layout', [
                'placement' => 'top',
            ])
            ->addSelect('columns', [
                'label' => 'Number of Columns',
                'instructions' => 'How many columns to display on desktop',
                'choices' => [
                    '1' => '1 Column',
                    '2' => '2 Columns', 
                    '3' => '3 Columns',
                    '4' => '4 Columns',
                ],
                'default_value' => '3',
                'required' => 1,
            ])
            ->addPartial(CardOptions::withConfig([
                'colors' => [ThemeVariant::PURPLE, ThemeVariant::CYAN, ThemeVariant::YELLOW, ThemeVariant::GREEN]
            ]))
            ->addSelect('card_size', [
                'label' => 'Card Size',
                'instructions' => 'Choose the size for the cards',
                'choices' => [
                    'small' => 'Small',
                    'default' => 'Default',
                    'large' => 'Large',
                ],
                'default_value' => 'default',
                'required' => 1,
            ])
            

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::withConfig([
                'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK]
            ]));

        return $featureCards->build();
    }

    /**
     * Get field methods
     */
    public function getHeading()
    {
        return get_field('heading');
    }

    public function getCards()
    {
        return get_field('cards') ?: [];
    }

    public function getCardColor()
    {
        return get_field('card_color') ?: ThemeVariant::CYAN;
    }

    public function getCardSize()
    {
        return get_field('card_size') ?: 'default';
    }

    public function getColumns()
    {
        return get_field('columns') ?: '3';
    }

    public function getSectionSize()
    {
        return get_field('section_size') ?: 'md';
    }

    public function getTheme()
    {
        return get_field('theme') ?: 'light';
    }

    public function getImageRatio()
    {
        return null;
    }
}
