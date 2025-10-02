<?php

namespace App\Blocks;

use App\Enums\ThemeVariant;
use App\Fields\Partials\CardOptions;
use App\Fields\Partials\SectionHeading;
use App\Fields\Partials\SectionOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class TestimonialsSliderBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Testimonials Slider';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A slider showcasing client testimonials with company logos and ratings.';

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
    public $icon = 'testimonial';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['testimonials', 'slider', 'reviews', 'clients'];

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

            // Content
            'testimonials' => $this->getTestimonials(),
            'count' => $this->getCount(),
            
            // Display Options
            'display_layout' => $this->getDisplayLayout(),
            'card_color' => $this->getCardColor(),
            'show_company_logos' => $this->getShowCompanyLogos(),
            'show_ratings' => $this->getShowRatings(),
            
            // Slider Settings
            'autoplay' => $this->getAutoplay(),
            'autoplay_delay' => $this->getAutoplayDelay(),
            'show_navigation' => $this->getShowNavigation(),
            
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
        $testimonials = Builder::make('testimonials_slider');

        $testimonials
            ->addTab('Section Heading', [
                'placement' => 'top',
            ])
            ->addPartial(SectionHeading::class)

            ->addTab('Content', [
                'placement' => 'top',
            ])
            ->addNumber('count', [
                'label' => 'Number of Testimonials',
                'instructions' => 'How many testimonials to display (0 = all)',
                'default_value' => 6,
                'min' => 0,
                'max' => 20,
            ])
            
            ->addTab('Display Options', [
                'placement' => 'top',
            ])
            ->addRadio('display_layout', [
                'label' => 'Display Layout',
                'instructions' => 'Choose how to display testimonials',
                'choices' => [
                    'slider' => 'Slider (Multiple testimonials)',
                    'single' => 'Single (One centered testimonial)',
                ],
                'default_value' => 'slider',
                'layout' => 'horizontal',
                'required' => 1,
            ])
            ->addPartial(CardOptions::withConfig([
                'colors' => [ThemeVariant::PURPLE, ThemeVariant::CYAN, ThemeVariant::YELLOW, ThemeVariant::GREEN]
            ]))
            ->addTrueFalse('show_company_logos', [
                'label' => 'Show Company Logos',
                'instructions' => 'Display company logos in testimonials',
                'default_value' => 1,
                'ui' => 1,
            ])
            ->addTrueFalse('show_ratings', [
                'label' => 'Show Ratings',
                'instructions' => 'Display star ratings',
                'default_value' => 1,
                'ui' => 1,
            ])
            
            ->addTab('Slider Settings', [
                'placement' => 'top',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'display_layout',
                            'operator' => '==',
                            'value' => 'slider',
                        ],
                    ],
                ],
            ])
            ->addTrueFalse('autoplay', [
                'label' => 'Autoplay',
                'instructions' => 'Automatically advance slides',
                'default_value' => 1,
                'ui' => 1,
            ])
            ->addNumber('autoplay_delay', [
                'label' => 'Autoplay Delay (seconds)',
                'instructions' => 'Time between slides in seconds',
                'default_value' => 5,
                'min' => 2,
                'max' => 10,
                'conditional_logic' => [
                    [
                        [
                            'field' => 'autoplay',
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ],
                ],
            ])
            ->addTrueFalse('show_navigation', [
                'label' => 'Show Navigation Arrows',
                'instructions' => 'Display previous/next arrows',
                'default_value' => 1,
                'ui' => 1,
            ])
            
            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::withConfig([
                'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK]
            ]));

        return $testimonials->build();
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
     * Get testimonials from the database.
     *
     * @return array
     */
    public function getTestimonials()
    {
        $count = $this->getCount();
        $displayLayout = $this->getDisplayLayout();

        // If single layout, only get 1 testimonial
        $postsPerPage = $displayLayout === 'single' ? 1 : ($count ?: -1);

        return get_posts([
            'post_type' => 'testimonial',
            'posts_per_page' => $postsPerPage,
            'post_status' => 'publish',
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ]);
    }

    /**
     * Get the count field.
     *
     * @return int
     */
    public function getCount()
    {
        return get_field('count') ?: 6;
    }

    /**
     * Get the display layout field.
     *
     * @return string
     */
    public function getDisplayLayout()
    {
        return get_field('display_layout') ?: 'slider';
    }

    /**
     * Get the card color field.
     *
     * @return string
     */
    public function getCardColor()
    {
        return get_field('card_color') ?: ThemeVariant::PURPLE;
    }

    /**
     * Get the autoplay field.
     *
     * @return bool
     */
    public function getAutoplay()
    {
        return get_field('autoplay') !== false ? get_field('autoplay') : true;
    }

    /**
     * Get the autoplay delay field.
     *
     * @return int
     */
    public function getAutoplayDelay()
    {
        return get_field('autoplay_delay') ?: 5;
    }

    /**
     * Get the show navigation field.
     *
     * @return bool
     */
    public function getShowNavigation()
    {
        return get_field('show_navigation') !== null ? get_field('show_navigation') : true;
    }

    /**
     * Get the show company logos field.
     *
     * @return bool
     */
    public function getShowCompanyLogos()
    {
        return get_field('show_company_logos') !== false ? get_field('show_company_logos') : true;
    }

    /**
     * Get the show ratings field.
     *
     * @return bool
     */
    public function getShowRatings()
    {
        return get_field('show_ratings') !== false ? get_field('show_ratings') : true;
    }

    /**
     * Get the section size field.
     *
     * @return string
     */
    public function getSectionSize()
    {
        return get_field('section_size') ?: 'lg';
    }

    /**
     * Get the theme field.
     *
     * @return string
     */
    public function getTheme()
    {
        return get_field('theme') ?: 'green';
    }
}