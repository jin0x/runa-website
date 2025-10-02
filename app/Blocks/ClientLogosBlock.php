<?php

namespace App\Blocks;

use App\Enums\ThemeVariant;
use App\Fields\Partials\SectionHeading;
use App\Fields\Partials\SectionOptions;
use App\Fields\Partials\GridOptions;
use App\Fields\Partials\SliderOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class ClientLogosBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Client Logos';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A block to display client or partner logos.';

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
    public $icon = 'id-alt';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['client', 'logo', 'partner', 'brands'];

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

            // Client Logos Fields
            'layout_type' => $this->getLayoutType(),
            'logos' => $this->getLogos(),

            // Grid Options
            'grid_columns' => $this->getGridColumns(),
            'grid_gap' => $this->getGridGap(),

            // Slider Options
            'slider_navigation' => $this->getSliderNavigation(),
            'slider_pagination' => $this->getSliderPagination(),
            'slider_loop' => $this->getSliderLoop(),
            'slider_autoplay_delay' => $this->getSliderAutoplayDelay(),
            'slider_space_between' => $this->getSliderSpaceBetween(),
            'slider_mobile_slides' => $this->getSliderMobileSlides(),
            'slider_tablet_slides' => $this->getSliderTabletSlides(),
            'slider_desktop_slides' => $this->getSliderDesktopSlides(),

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
        $clientLogos = Builder::make('client_logos');

        $clientLogos
            ->addTab('Section Heading', [
                'placement' => 'top',
            ])
            ->addPartial(SectionHeading::class)

            ->addTab('Logos', [
                'placement' => 'top',
            ])
            ->addSelect('layout_type', [
                'label' => 'Layout Type',
                'instructions' => 'Choose how to display the logos',
                'choices' => [
                    'grid' => 'Grid',
                    'slider' => 'Slider',
                    'marquee' => 'Marquee',
                ],
                'default_value' => 'grid',
                'required' => 1,
            ])
            ->addRepeater('logos', [
                'label' => 'Client Logos',
                'instructions' => 'Add client or partner logos',
                'min' => 1,
                'max' => 20,
                'layout' => 'block',
                'button_label' => 'Add Logo',
            ])
            ->addImage('logo', [
                'label' => 'Logo',
                'instructions' => 'Upload or select a logo image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'required' => 1,
            ])
            ->addLink('link', [
                'label' => 'Link',
                'instructions' => 'Optional link for the logo',
                'return_format' => 'array',
                'required' => 0,
            ])
            ->addText('alt_text', [
                'label' => 'Alt Text',
                'instructions' => 'Alternative text for the logo (for accessibility)',
                'required' => 0,
            ])
            ->endRepeater()

            ->addTab('Grid Options', [
                'placement' => 'top',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'layout_type',
                            'operator' => '==',
                            'value' => 'grid',
                        ],
                    ],
                ],
            ])
            ->addPartial(GridOptions::class)

            ->addTab('Slider Options', [
                'placement' => 'top',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'layout_type',
                            'operator' => '==',
                            'value' => 'slider',
                        ],
                    ],
                ],
            ])
            ->addPartial(SliderOptions::class)

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::withConfig([
                'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK, ThemeVariant::CYAN, ThemeVariant::YELLOW]
            ]));

        return $clientLogos->build();
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
     * Logos getters
     */
    public function getLayoutType()
    {
        return get_field('layout_type') ?: 'grid';
    }

    public function getLogos()
    {
        return get_field('logos') ?: [];
    }

    /**
     * Grid options getters
     */
    public function getGridColumns()
    {
        return get_field('grid_columns') ?: '4';
    }

    public function getGridGap()
    {
        return get_field('grid_gap') ?: 'lg';
    }

    /**
     * Slider options getters
     */
    public function getSliderNavigation()
    {
        return get_field('slider_navigation') ?? true;
    }

    public function getSliderPagination()
    {
        return get_field('slider_pagination') ?? true;
    }

    public function getSliderLoop()
    {
        return get_field('slider_loop') ?? true;
    }

    public function getSliderAutoplayDelay()
    {
        return get_field('slider_autoplay_delay') ?: 5000;
    }

    public function getSliderSpaceBetween()
    {
        return get_field('slider_space_between') ?: 30;
    }

    public function getSliderMobileSlides()
    {
        return get_field('slider_mobile_slides') ?: 2;
    }

    public function getSliderTabletSlides()
    {
        return get_field('slider_tablet_slides') ?: 3;
    }

    public function getSliderDesktopSlides()
    {
        return get_field('slider_desktop_slides') ?: 5;
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
