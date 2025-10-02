<?php

namespace App\Blocks;

use App\Enums\ThemeVariant;
use App\Fields\Partials\SectionOptions;
use App\Fields\Partials\CardOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class SectionCardBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Section Card';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A split layout with image and themed content card featuring a feature list.';

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
    public $icon = 'columns';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['split', 'content', 'image', 'features', 'list'];

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
            'image' => $this->getImage(),
            'heading' => $this->getHeading(),
            'description' => $this->getDescriptionText(),
            'features' => $this->getFeatures(),
            'theme' => $this->getTheme(),
            'card_color' => $this->getCardColor(),
            'reverse_layout' => $this->getReverseLayout(),
            'section_size' => $this->getSectionSize(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $sectionCard = Builder::make('section_card');

        $sectionCard
            ->addTab('Content', [
                'placement' => 'top',
            ])
            ->addImage('image', [
                'label' => 'Image',
                'instructions' => 'Upload or select an image for the split content block',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'required' => 1,
            ])
            ->addText('heading', [
                'label' => 'Heading',
                'instructions' => 'Main heading for the content area',
                'required' => 1,
            ])
            ->addTextarea('description', [
                'label' => 'Description',
                'instructions' => 'Description text that appears below the heading',
                'rows' => 4,
                'required' => 0,
            ])
            ->addRepeater('features', [
                'label' => 'Feature List',
                'instructions' => 'Add features with checkmark icons',
                'min' => 0,
                'max' => 10,
                'layout' => 'block',
                'button_label' => 'Add Feature',
            ])
            ->addText('feature_text', [
                'label' => 'Feature Text',
                'required' => 1,
            ])
            ->addSelect('feature_style', [
                'label' => 'Text Style',
                'instructions' => 'Choose the font weight for this feature',
                'choices' => [
                    'normal' => 'Normal',
                    'bold' => 'Bold',
                ],
                'default_value' => 'normal',
                'wrapper' => [
                    'width' => '30',
                ],
            ])
            ->endRepeater()

            ->addTab('Design', [
                'placement' => 'top',
            ])
            ->addTrueFalse('reverse_layout', [
                'label' => 'Reverse Layout',
                'instructions' => 'Switch the position of image and content (desktop only)',
                'default_value' => 0,
                'ui' => 1,
            ])

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::withConfig([
                'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK]
            ]))
            ->addPartial(CardOptions::withConfig([
                'colors' => [ThemeVariant::PURPLE, ThemeVariant::CYAN, ThemeVariant::YELLOW, ThemeVariant::GREEN]
            ]));

        return $sectionCard->build();
    }

    /**
     * Get the image field.
     */
    public function getImage()
    {
        return get_field('image');
    }

    /**
     * Get the heading field.
     */
    public function getHeading()
    {
        return get_field('heading');
    }

    /**
     * Get the description field.
     */
    public function getDescriptionText()
    {
        return get_field('description');
    }

    /**
     * Get the features field.
     */
    public function getFeatures()
    {
        return get_field('features') ?: [];
    }

    /**
     * Get the theme field.
     */
    public function getTheme()
    {
        return get_field('theme') ?: 'cyan';
    }

    /**
     * Get the reverse layout field.
     */
    public function getReverseLayout()
    {
        return get_field('reverse_layout') ?: false;
    }

    /**
     * Get the card color field.
     */
    public function getCardColor()
    {
        return get_field('card_color') ?: ThemeVariant::CYAN;
    }

    /**
     * Get the section size field.
     */
    public function getSectionSize()
    {
        return get_field('section_size') ?: 'md';
    }
}