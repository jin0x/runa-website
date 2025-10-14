<?php

namespace App\Blocks;

use App\Enums\ThemeVariant;
use App\Fields\Partials\SectionHeading;
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
            // Section Heading Fields
            'section_eyebrow' => $this->getSectionEyebrow(),
            'section_title' => $this->getSectionTitle(),
            'section_description' => $this->getSectionDescription(),

            // Content Fields
            'statistics_cards' => $this->getStatisticsCards(),
            'media_type' => $this->getMediaType(),
            'image' => $this->getImage(),
            'video' => $this->getVideo(),
            'lottie' => $this->getLottie(),
            'marquee_logos' => $this->getMarqueeLogos(),
            'grid_items' => $this->getGridItems(),
            'cta' => $this->getCta(),
            'accent_color' => $this->getAccentColor(),
            'columns' => $this->getColumns(),

            // Section Options
            'section_size' => $this->getSectionSize(),
            'theme' => $this->getTheme(),
            'arch_position' => $this->getArchPosition(),
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
            ->addTab('Section Heading', [
                'placement' => 'top',
            ])
            ->addPartial(SectionHeading::class)

            ->addTab('Content', [
                'placement' => 'top',
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
            ->addSelect('media_type', [
                'label' => 'Media Type',
                'instructions' => 'Select the type of media to display',
                'choices' => [
                    'image' => 'Image',
                    'video' => 'Video',
                    'lottie' => 'Lottie Animation',
                    'logo-marquee' => 'Logo Marquee (3 Lanes)',
                    'grid' => 'Grid Layout (4 Items)'
                ],
                'default_value' => 'image',
                'required' => 1,
            ])
            ->addImage('image', [
                'label' => 'Image',
                'instructions' => 'Select an image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'media_type',
                            'operator' => '==',
                            'value' => 'image',
                        ],
                    ],
                ],
            ])
            ->addFile('video', [
                'label' => 'Video',
                'instructions' => 'Upload or select a video file (MP4)',
                'return_format' => 'array',
                'library' => 'all',
                'mime_types' => 'mp4',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'media_type',
                            'operator' => '==',
                            'value' => 'video',
                        ],
                    ],
                ],
            ])
            ->addFile('lottie', [
                'label' => 'Lottie Animation',
                'instructions' => 'Upload or select a Lottie JSON file',
                'return_format' => 'array',
                'library' => 'all',
                'mime_types' => 'json',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'media_type',
                            'operator' => '==',
                            'value' => 'lottie',
                        ],
                    ],
                ],
            ])
            ->addRepeater('marquee_logos', [
                'label' => 'Marquee Logos',
                'instructions' => 'Add logos for the 3-lane marquee (minimum 10 recommended)',
                'min' => 10,
                'max' => 25,
                'layout' => 'block',
                'button_label' => 'Add Logo',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'media_type',
                            'operator' => '==',
                            'value' => 'logo-marquee',
                        ],
                    ],
                ],
            ])
            ->addImage('logo', [
                'label' => 'Logo',
                'instructions' => 'Upload or select a logo image',
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'required' => 1,
            ])
            ->addText('alt_text', [
                'label' => 'Alt Text',
                'instructions' => 'Alternative text for the logo (for accessibility)',
                'required' => 0,
            ])
            ->endRepeater()
            ->addRepeater('grid_items', [
                'label' => 'Grid Items',
                'instructions' => 'Add 4 items for the grid layout',
                'min' => 4,
                'max' => 4,
                'layout' => 'block',
                'button_label' => 'Add Grid Item',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'media_type',
                            'operator' => '==',
                            'value' => 'grid',
                        ],
                    ],
                ],
            ])
            ->addImage('grid_image', [
                'label' => 'Image',
                'instructions' => 'Upload or select an image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'required' => 1,
            ])
            ->addText('grid_title', [
                'label' => 'Title',
                'instructions' => 'Enter the title for this grid item',
                'required' => 1,
            ])
            ->addTextarea('grid_description', [
                'label' => 'Description',
                'instructions' => 'Enter the description for this grid item',
                'rows' => 3,
                'required' => 1,
            ])
            ->endRepeater()

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
            ->addPartial(SectionOptions::withConfig([
                'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK]
            ]));

        return $showcase->build();
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

    public function getMarqueeLogos()
    {
        return get_field('marquee_logos') ?: [];
    }

    public function getGridItems()
    {
        return get_field('grid_items') ?: [];
    }

    public function getCta()
    {
        return get_field('cta');
    }

    public function getAccentColor()
    {
        return get_field('accent_color') ?: 'green-neon';
    }

    public function getColumns()
    {
        $statistics = $this->getStatisticsCards();
        $count = count($statistics);

        // Automatically determine columns based on number of statistics (max 5)
        if ($count <= 2) {
            return '2';
        } elseif ($count === 3) {
            return '3';
        } elseif ($count === 4) {
            return '4';
        } else {
            return '5'; // 5 or more items
        }
    }

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