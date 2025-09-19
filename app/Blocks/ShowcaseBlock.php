<?php

namespace App\Blocks;

use App\Fields\Partials\SectionOptions;
use App\Fields\Partials\MediaComponent;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class ShowcaseBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Showcase';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A showcase block with statistics cards, media, and CTA.';

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
    public $icon = 'chart-bar';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['showcase', 'statistics', 'stats', 'metrics', 'cards'];

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
            'statistics_cards' => $this->getStatisticsCards(),
            'media_type' => $this->getMediaType(),
            'image' => $this->getImage(),
            'video' => $this->getVideo(),
            'lottie' => $this->getLottie(),
            'cta' => $this->getCta(),
            'accent_color' => $this->getAccentColor(),
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
        $showcase = Builder::make('showcase');

        $showcase
            ->addTab('Content', [
                'placement' => 'top',
            ])
            ->addText('eyebrow', [
                'label' => 'Eyebrow Text',
                'instructions' => 'Small text displayed above the heading (will be capitalized)',
                'required' => 0,
            ])
            ->addText('heading', [
                'label' => 'Main Heading',
                'instructions' => 'Main heading for the showcase section',
                'required' => 1,
            ])
            ->addRepeater('statistics_cards', [
                'label' => 'Statistics Cards',
                'instructions' => 'Add statistics cards (1-6 recommended)',
                'min' => 0,
                'max' => 6,
                'layout' => 'block',
                'button_label' => 'Add Statistic Card',
            ])
            ->addImage('icon', [
                'label' => 'Icon',
                'instructions' => 'Upload an icon for this statistic',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'required' => 0,
            ])
            ->addText('statistic', [
                'label' => 'Statistic',
                'instructions' => 'The main number/statistic (e.g., "190+", "1.5B+", "5000+")',
                'required' => 1,
            ])
            ->addText('description', [
                'label' => 'Description',
                'instructions' => 'Brief description below the statistic (max ~8 words)',
                'required' => 1,
            ])
            ->endRepeater()

            ->addTab('Media', [
                'placement' => 'top',
            ])
            ->addPartial(MediaComponent::class)

            ->addTab('Call to Action', [
                'placement' => 'top',
            ])
            ->addLink('cta', [
                'label' => 'Call to Action Button',
                'instructions' => 'Add a call to action button',
                'return_format' => 'array',
                'required' => 0,
            ])

            ->addTab('Design', [
                'placement' => 'top',
            ])
            ->addSelect('accent_color', [
                'label' => 'Accent Color',
                'instructions' => 'Choose the accent color for text and elements',
                'choices' => [
                    'green-neon' => 'Green Neon',
                    'green-soft' => 'Green Soft',
                    'yellow' => 'Yellow',
                    'pink' => 'Pink',
                    'purple' => 'Purple',
                    'cyan' => 'Cyan',
                ],
                'default_value' => 'green-neon',
                'required' => 1,
            ])

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::class);

        return $showcase->build();
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

    public function getStatisticsCards()
    {
        return get_field('statistics_cards') ?: [];
    }

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

    public function getCta()
    {
        return get_field('cta');
    }

    public function getAccentColor()
    {
        return get_field('accent_color') ?: 'green-neon';
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