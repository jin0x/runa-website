<?php

namespace App\Blocks;

use App\Fields\Partials\SectionHeading;
use App\Fields\Partials\SectionOptions;
use App\Enums\ThemeVariant;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class StackingCardsBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Stacking Cards';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A scroll-locked component with stacking cards animation.';

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
    public $icon = 'stack';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['cards', 'stack', 'scroll', 'lock', 'animation', 'interactive'];

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

            // Stacking Cards Fields
            'cards' => $this->getCards(),
            'mobile_breakpoint' => $this->getMobileBreakpoint(),

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
        $stackingCards = Builder::make('stacking_cards');

        $stackingCards
            ->addTab('Section Heading', [
                'placement' => 'top',
            ])
            ->addPartial(SectionHeading::class)

            ->addTab('Cards', [
                'placement' => 'top',
            ])
            ->addRepeater('cards', [
                'label' => 'Cards',
                'instructions' => 'Add cards that will stack with scroll-locked behavior (1-4 cards recommended)',
                'min' => 1,
                'max' => 4,
                'layout' => 'block',
                'button_label' => 'Add Card',
            ])
            ->addText('title', [
                'label' => 'Title',
                'instructions' => 'Card title/heading',
                'required' => 1,
            ])
            ->addTextarea('description', [
                'label' => 'Description',
                'instructions' => 'Card description text',
                'required' => 1,
                'rows' => 4,
            ])
            ->addImage('image', [
                'label' => 'Image',
                'instructions' => 'Card image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'required' => 1,
            ])
            ->addLink('cta', [
                'label' => 'Call to Action (Optional)',
                'instructions' => 'Optional link for the card',
                'return_format' => 'array',
            ])
            ->endRepeater()

            ->addTab('Behavior Settings', [
                'placement' => 'top',
            ])
            ->addNumber('mobile_breakpoint', [
                'label' => 'Mobile Breakpoint (px)',
                'instructions' => 'Below this width, the stacking effect will be disabled',
                'default_value' => 996,
                'min' => 320,
                'max' => 1200,
                'step' => 1,
            ])

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::withConfig([
                'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK]
            ]));

        return $stackingCards->build();
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
     * Stacking cards getters
     */
    public function getCards()
    {
        return get_field('cards') ?: [];
    }

    public function getMobileBreakpoint()
    {
        return get_field('mobile_breakpoint') ?: 996;
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
