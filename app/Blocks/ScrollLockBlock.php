<?php

namespace App\Blocks;

use App\Fields\Partials\SectionHeading;
use App\Fields\Partials\SectionOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class ScrollLockBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Scroll Lock';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A scroll-locked component with progress indicator and changing images.';

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
    public $icon = 'move';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['scroll', 'lock', 'progress', 'images', 'interactive'];

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

            // Scroll Lock Fields
            'sections' => $this->getSections(),
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
        $scrollLock = Builder::make('scroll_lock');

        $scrollLock
            ->addTab('Section Heading', [
                'placement' => 'top',
            ])
            ->addPartial(SectionHeading::class)

            ->addTab('Content Sections', [
                'placement' => 'top',
            ])
            ->addRepeater('sections', [
                'label' => 'Content Sections',
                'instructions' => 'Add sections that will be displayed with scroll-locked behavior',
                'min' => 2,
                'max' => 6,
                'layout' => 'block',
                'button_label' => 'Add Section',
            ])
            ->addText('title', [
                'label' => 'Title',
                'instructions' => 'Main heading for this section',
                'required' => 1,
            ])
            ->addTextarea('description', [
                'label' => 'Description',
                'instructions' => 'Descriptive text for this section',
                'required' => 1,
                'rows' => 4,
            ])
            ->addImage('image', [
                'label' => 'Image',
                'instructions' => 'Image to display for this section',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'required' => 1,
            ])
            ->endRepeater()

            ->addTab('Behavior Settings', [
                'placement' => 'top',
            ])
            ->addNumber('mobile_breakpoint', [
                'label' => 'Mobile Breakpoint (px)',
                'instructions' => 'Below this width, the scroll-lock effect will be disabled',
                'default_value' => 996,
                'min' => 320,
                'max' => 1200,
                'step' => 1,
            ])

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::class);

        return $scrollLock->build();
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
     * Scroll lock getters
     */
    public function getSections()
    {
        return get_field('sections') ?: [];
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