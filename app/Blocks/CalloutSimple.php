<?php

namespace App\Blocks;

use App\Enums\ThemeVariant;
use App\Fields\Partials\SectionOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class CalloutSimple extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Callout Simple';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple callout section with background image, heading, text, and CTA buttons.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'text';

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
    public $post_types = ['page'];

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
     *
     * @return array
     */
    public function with()
    {
        return [
            'eyebrow' => $this->getEyebrow(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'ctas' => $this->getCtas(),
            'section_size' => $this->getSectionSize(),
            'theme' => $this->getTheme(),
            'background_image' => $this->getBackgroundImage(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $calloutSimple = Builder::make('callout_simple');

        $calloutSimple
            ->addMessage('Callout Simple', 'A simple callout section with background image, heading, text, and CTA buttons.')
            ->addTab('Content', [
                'placement' => 'top',
            ])
                ->addText('eyebrow', [
                    'label' => 'Eyebrow',
                    'instructions' => 'Small text displayed above the title',
                    'required' => 0,
                ])
                ->addText('title', [
                    'label' => 'Title',
                    'instructions' => 'Main heading for the callout',
                ])
                ->addTextarea('content', [
                    'label' => 'Content',
                    'instructions' => 'Main content for the callout',
                    'required' => 0,
                    'tabs' => 'visual',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                ])
                ->addRepeater('ctas', [
                    'label' => 'Call to Actions',
                    'instructions' => 'Add one or more call to action buttons',
                    'min' => 0,
                    'max' => 2,
                    'layout' => 'block',
                    'button_label' => 'Add Call to Action',
                ])
                    ->addLink('cta', [
                        'label' => 'Button',
                        'return_format' => 'array',
                    ])
                ->endRepeater()
                ->addImage('background_image', [
                    'label' => 'Background Image',
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ])

            ->addTab('Settings', [
                'placement' => 'top',
            ])
                ->addPartial(SectionOptions::withConfig([
                'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK]
            ]));

        return $calloutSimple->build();
    }

    /**
     * Return the eyebrow field.
     *
     * @return string
     */
    public function getEyebrow()
    {
        return get_field('eyebrow');
    }

    /**
     * Return the title field.
     *
     * @return string
     */
    public function getTitle()
    {
        return get_field('title');
    }

    /**
     * Return the content field.
     *
     * @return string
     */
    public function getContent()
    {
        return get_field('content');
    }

    /**
     * Return the CTA fields.
     *
     * @return array
     */
    public function getCtas()
    {
        return get_field('ctas') ?: [];
    }


    /**
     * Return the section size field.
     *
     * @return string
     */
    public function getSectionSize()
    {
        return get_field('section_size') ?: 'md';
    }

    /**
     * Return the theme field.
     *
     * @return string
     */
    public function getTheme()
    {
        return get_field('theme') ?: 'light';
    }

    /**
     * Return the background image field.
     *
     * @return array|null
     */
    public function getBackgroundImage()
    {
        return get_field('background_image');
    }
}
