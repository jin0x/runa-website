<?php

namespace App\Blocks;

use App\Enums\ThemeVariant;
use App\Fields\Partials\SectionOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class ActionCardsBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Action Cards';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Action cards block with customizable cards and CTAs.';

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
    public $keywords = ['action', 'cards', 'cta', 'call-to-action'];

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
            'card_background_color' => $this->getCardBackgroundColor(),
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
        $actionCards = Builder::make('action_cards');

        $actionCards
            ->addTab('Content', [
                'placement' => 'top',
            ])
            ->addText('heading', [
                'label' => 'Section Heading',
                'instructions' => 'Main heading for the action cards section',
                'required' => 1,
            ])
            ->addRepeater('cards', [
                'label' => 'Action Cards',
                'instructions' => 'Add action cards (1-4 recommended)',
                'min' => 1,
                'max' => 4,
                'layout' => 'block',
                'button_label' => 'Add Action Card',
            ])
            ->addImage('icon', [
                'label' => 'Icon',
                'instructions' => 'Upload an icon for this card',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'required' => 1,
            ])
            ->addText('title', [
                'label' => 'Card Title',
                'instructions' => 'Title for this action card',
                'required' => 1,
            ])
            ->addTextarea('description', [
                'label' => 'Description (Optional)',
                'instructions' => 'Optional description text for the card',
                'rows' => 2,
                'required' => 0,
            ])
            ->addLink('cta', [
                'label' => 'Call to Action',
                'instructions' => 'Button link for this card',
                'return_format' => 'array',
                'required' => 1,
            ])
            ->endRepeater()

            ->addTab('Design', [
                'placement' => 'top',
            ])
            ->addSelect('card_background_color', [
                'label' => 'Card Background Color',
                'instructions' => 'Choose the background color for the cards',
                'choices' => [
                    'yellow' => 'Yellow',
                    'green-neon' => 'Green Neon',
                    'green-soft' => 'Green Soft',
                    'cyan' => 'Cyan',
                    'white' => 'White',
                ],
                'default_value' => 'yellow',
                'required' => 1,
            ])

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::withConfig([
                'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK]
            ]));

        return $actionCards->build();
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

    public function getCardBackgroundColor()
    {
        return get_field('card_background_color') ?: 'yellow';
    }

    public function getSectionSize()
    {
        return get_field('section_size') ?: 'md';
    }

    public function getTheme()
    {
        return get_field('theme') ?: 'light';
    }
}