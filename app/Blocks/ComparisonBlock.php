<?php

namespace App\Blocks;

use App\Fields\Partials\SectionOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class ComparisonBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Comparison';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A comparison block showing two sides with feature lists.';

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
    public $keywords = ['comparison', 'versus', 'features', 'compare'];

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
            'left_side' => $this->getLeftSide(),
            'right_side' => $this->getRightSide(),
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
        $comparison = Builder::make('comparison');

        $comparison
            ->addTab('Section Heading', [
                'placement' => 'top',
            ])
            ->addText('eyebrow', [
                'label' => 'Eyebrow Text',
                'instructions' => 'Small text displayed above the heading',
                'required' => 0,
            ])
            ->addText('heading', [
                'label' => 'Main Heading',
                'instructions' => 'Main heading for the comparison section',
                'required' => 1,
            ])
            ->addText('subtitle', [
                'label' => 'Subtitle',
                'instructions' => 'Subtitle text below the heading',
                'required' => 0,
            ])

            ->addTab('Left Side', [
                'placement' => 'top',
            ])
            ->addGroup('left_side', [
                'label' => 'Left Side Content',
            ])
            ->addText('eyebrow', [
                'label' => 'Eyebrow',
                'required' => 0,
            ])
            ->addText('title', [
                'label' => 'Title',
                'required' => 1,
            ])
            ->addTextarea('description', [
                'label' => 'Description',
                'rows' => 3,
                'required' => 0,
            ])
            ->addRepeater('features', [
                'label' => 'Features',
                'min' => 1,
                'max' => 10,
                'layout' => 'block',
                'button_label' => 'Add Feature',
            ])
            ->addText('feature_text', [
                'label' => 'Feature Text',
                'required' => 1,
            ])
            ->endRepeater()
            ->addSelect('icon_type', [
                'label' => 'Icon Type',
                'choices' => [
                    'checkmark' => 'Green Checkmark',
                    'cross' => 'Pink Cross',
                ],
                'default_value' => 'checkmark',
            ])
            ->endGroup()

            ->addTab('Right Side', [
                'placement' => 'top',
            ])
            ->addGroup('right_side', [
                'label' => 'Right Side Content',
            ])
            ->addText('eyebrow', [
                'label' => 'Eyebrow',
                'required' => 0,
            ])
            ->addText('title', [
                'label' => 'Title',
                'required' => 1,
            ])
            ->addTextarea('description', [
                'label' => 'Description',
                'rows' => 3,
                'required' => 0,
            ])
            ->addRepeater('features', [
                'label' => 'Features',
                'min' => 1,
                'max' => 10,
                'layout' => 'block',
                'button_label' => 'Add Feature',
            ])
            ->addText('feature_text', [
                'label' => 'Feature Text',
                'required' => 1,
            ])
            ->endRepeater()
            ->addSelect('icon_type', [
                'label' => 'Icon Type',
                'choices' => [
                    'checkmark' => 'Green Checkmark',
                    'cross' => 'Pink Cross',
                ],
                'default_value' => 'cross',
            ])
            ->endGroup()

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::class);

        return $comparison->build();
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

    public function getLeftSide()
    {
        return get_field('left_side');
    }

    public function getRightSide()
    {
        return get_field('right_side');
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