<?php

namespace App\Blocks;

use App\Enums\ThemeVariant;
use App\Fields\Partials\SectionHeading;
use App\Fields\Partials\SectionOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial; 

class Faq extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Faq';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Displays a list of frequently asked questions in an accordion format. Ideal for providing answers to common inquiries about your product or service.';

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
    public $icon = 'editor-ul';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = [];

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
     * The ancestor block type allow list.
     *
     * @var array
     */
    public $ancestor = [];

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
    public $align = '';

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
     * The default block spacing.
     *
     * @var array
     */
    public $spacing = [
        'padding' => null,
        'margin' => null,
    ];

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
        'anchor' => false,
        'mode' => true,
        'multiple' => true,
        'jsx' => true,
        'color' => [
            'background' => false,
            'text' => false,
            'gradients' => false,
        ],
        'spacing' => [
            'padding' => false,
            'margin' => false,
        ],
    ];

     /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        return [
            // Section Heading Fields
            'section_eyebrow' => $this->getSectionEyebrow(),
            'section_title' => $this->getSectionTitle(),
            'section_description' => $this->getSectionDescription(),

            // Content Fields (repeater)
            'faq_items' => $this->getFAQItems(), 

            // Background Image
            'bottom_background_image' => $this->getBottomBackgroundImage(),

            // Section Options
            'section_size' => $this->getSectionSize(),
            'theme' => $this->getTheme(),
            'arch_position' => $this->getArchPosition(),
        ];
    }

     /**
     * The block field group.
     */
    public function fields(): array
    {
        $faq = Builder::make('faq');

        $faq
            ->addMessage('FAQ', 'Displays a list of frequently asked questions in an accordion format. Ideal for providing answers to common inquiries about your product or service.')
            
            ->addTab('Section Heading', [
                'placement' => 'top',
            ])
            ->addPartial(SectionHeading::class)

            ->addTab('Content', [
                'placement' => 'top',
            ])
                ->addRepeater('faq_items', [
                        'label' => 'FAQ Items',
                        'layout' => 'block',
                        'button_label' => 'Add FAQ Item',
                        'instructions' => 'Add your questions and answers',
                    ])
                    ->addText('question', [
                        'label' => 'Question',
                        'instructions' => 'Title of the Question',
                    ])
                    ->addWysiwyg('answer', [
                        'label' => 'Answer',
                        'instructions' => 'Main body text for the answer',
                        'required' => 0,
                        'tabs' => 'visual',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                    ])
                    ->addTrueFalse('initially_open', [
                        'label' => 'Initially Open',
                        'instructions' => 'Should this item be expanded by default?',
                        'default_value' => 0,
                        'ui' => 1,
                    ])

                ->endRepeater()

            ->addTab('Background', [
            'placement' => 'top',
            ])
            ->addImage('bottom_background_image', [
                'label' => 'Bottom Background Image',
                'instructions' => 'Optional decorative image displayed at the bottom of the FAQ section',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ])

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::withConfig([
                'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK]
            ]));
        return $faq->build();
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
     * Section content getters
     */

    public function getFAQItems()
    {
        return get_field('faq_items') ?: [];
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

    /**
     * Get the bottom background image
     */
    public function getBottomBackgroundImage()
    {
        return get_field('bottom_background_image');
    }

    public function getArchPosition()
    {
        return get_field('arch_position') ?: 'none';
    }
}
