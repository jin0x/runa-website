<?php

namespace App\Blocks;

use App\Fields\Partials\SectionOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class PricingCardsBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Pricing Cards';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A pricing cards block with customizable pricing tiers.';

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
    public $icon = 'money-alt';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['pricing', 'plans', 'tiers', 'cost'];

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
            'eyebrow' => $this->getEyebrow(),
            'heading' => $this->getHeading(),
            'subtitle' => $this->getSubtitle(),
            'pricing_cards' => $this->getPricingCards(),
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
        $pricingCards = Builder::make('pricing_cards');

        $pricingCards
            ->addTab('Section Header', [
                'placement' => 'top',
            ])
            ->addText('eyebrow', [
                'label' => 'Eyebrow Text',
                'instructions' => 'Small text displayed above the heading',
                'required' => 0,
            ])
            ->addText('heading', [
                'label' => 'Section Heading',
                'instructions' => 'Main heading for the pricing section',
                'required' => 1,
            ])
            ->addTextarea('subtitle', [
                'label' => 'Subtitle',
                'instructions' => 'Description text below the heading',
                'rows' => 3,
                'required' => 0,
            ])

            ->addTab('Pricing Cards', [
                'placement' => 'top',
            ])
            ->addRepeater('pricing_cards', [
                'label' => 'Pricing Cards',
                'instructions' => 'Add pricing cards (1-4 recommended)',
                'min' => 1,
                'max' => 4,
                'layout' => 'block',
                'button_label' => 'Add Pricing Card',
            ])
            ->addImage('icon', [
                'label' => 'Plan Icon',
                'instructions' => 'Upload an icon for this pricing plan',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'required' => 1,
            ])
            ->addText('title', [
                'label' => 'Plan Title',
                'instructions' => 'Name of this pricing plan',
                'required' => 1,
            ])
            ->addTextarea('description', [
                'label' => 'Plan Description',
                'instructions' => 'Brief description of this plan',
                'rows' => 2,
                'required' => 0,
            ])
            ->addRepeater('pricing_items', [
                'label' => 'Pricing Items',
                'instructions' => 'Add pricing lines (monthly fee, setup fee, etc.)',
                'min' => 1,
                'max' => 5,
                'layout' => 'table',
                'button_label' => 'Add Pricing Item',
            ])
            ->addText('price', [
                'label' => 'Price',
                'instructions' => 'e.g., "$0/mo", "$2,000/mo", "$5,000"',
                'required' => 1,
            ])
            ->addText('label', [
                'label' => 'Price Label',
                'instructions' => 'e.g., "platform fee", "setup fee", "issuance cost/code"',
                'required' => 0,
            ])
            ->endRepeater()
            ->addLink('cta', [
                'label' => 'Call to Action',
                'instructions' => 'Button for this pricing plan',
                'return_format' => 'array',
                'required' => 1,
            ])
            ->addText('features_title', [
                'label' => 'Features Section Title',
                'instructions' => 'e.g., "Includes:", "All benefits of Starter plus:"',
                'required' => 0,
            ])
            ->addRepeater('features', [
                'label' => 'Features List',
                'instructions' => 'Add features included in this plan',
                'min' => 0,
                'max' => 15,
                'layout' => 'table',
                'button_label' => 'Add Feature',
            ])
            ->addText('text', [
                'label' => 'Feature Text',
                'required' => 1,
            ])
            ->endRepeater()
            ->addText('asterisk_note', [
                'label' => 'Asterisk Note',
                'instructions' => 'Small note at bottom (e.g., "*monthly minimum applies")',
                'required' => 0,
            ])
            ->addTrueFalse('is_popular', [
                'label' => 'Most Popular Plan',
                'instructions' => 'Mark this as the most popular/featured plan',
                'default_value' => 0,
                'ui' => 1,
            ])
            ->endRepeater()

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::class);

        return $pricingCards->build();
    }

    /**
     * Get field methods
     */
    public function getEyebrow()
    {
        return get_field('eyebrow');
    }

    public function getHeading()
    {
        return get_field('heading');
    }

    public function getSubtitle()
    {
        return get_field('subtitle');
    }

    public function getPricingCards()
    {
        return get_field('pricing_cards') ?: [];
    }

    public function getSectionSize()
    {
        return get_field('section_size') ?: 'md';
    }

    public function getTheme()
    {
        return get_field('theme') ?: 'dark';
    }
}