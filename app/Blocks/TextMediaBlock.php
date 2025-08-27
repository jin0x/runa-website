<?php

namespace App\Blocks;

use App\Fields\Partials\SectionHeading;
use App\Fields\Partials\MediaComponent;
use App\Fields\Partials\SectionOptions;
use App\Fields\Partials\CTALink;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class TextMediaBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Text Media';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A block with text and media side by side.';

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
    public $icon = 'align-pull-left';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['text', 'media', 'image', 'video', 'content'];

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

            // Content Fields
            'content_eyebrow' => $this->getContentEyebrow(),
            'content_heading' => $this->getContentHeading(),
            'content_text' => $this->getContentText(),
            'ctas' => $this->getCtas(),
            'reverse_layout' => $this->getReverseLayout(),

            // Media Fields
            'media_type' => $this->getMediaType(),
            'image' => $this->getImage(),
            'video' => $this->getVideo(),
            'lottie' => $this->getLottie(),

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
        $textMedia = Builder::make('text_media');

        $textMedia
            ->addTab('Section Heading', [
                'placement' => 'top',
            ])
            ->addPartial(SectionHeading::class)

            ->addTab('Content', [
                'placement' => 'top',
            ])
            ->addText('content_eyebrow', [
                'label' => 'Eyebrow',
                'instructions' => 'Small text displayed above the heading',
            ])
            ->addText('content_heading', [
                'label' => 'Heading',
                'instructions' => 'Main heading for the content section',
                'required' => 1,
            ])
            ->addWysiwyg('content_text', [
                'label' => 'Text Content',
                'instructions' => 'Main body text for the content section',
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
            ->addTrueFalse('reverse_layout', [
                'label' => 'Reverse Layout',
                'instructions' => 'Switch the position of text and media (desktop only)',
                'default_value' => 0,
                'ui' => 1,
            ])

            ->addTab('Media', [
                'placement' => 'top',
            ])
            ->addPartial(MediaComponent::class)

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::class);

        return $textMedia->build();
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
     * Content getters
     */
    public function getContentEyebrow()
    {
        return get_field('content_eyebrow');
    }

    public function getContentHeading()
    {
        return get_field('content_heading');
    }

    public function getContentText()
    {
        return get_field('content_text');
    }

    public function getCtas()
    {
        return get_field('ctas') ?: [];
    }

    public function getReverseLayout()
    {
        return get_field('reverse_layout') ?: false;
    }

    /**
     * Media getters
     */
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
