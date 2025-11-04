<?php

namespace App\Blocks;

use App\Enums\ThemeVariant;
use App\Fields\Partials\SectionHeading;
use App\Fields\Partials\SectionOptions;
use App\Fields\Partials\MediaComponent;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class ContentMedia extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Content Media';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A split layout with content (heading, rich text, CTA) on the left and media on the right.';

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
    public $keywords = ['content', 'media', 'split', 'layout'];

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
            'section_eyebrow' => $this->getSectionEyebrow(),
            'section_heading' => $this->getSectionHeading(),
            'section_subtitle' => $this->getSectionSubtitle(),
            'content_heading' => $this->getContentHeading(),
            'content_text' => $this->getContentText(),
            'list_items' => $this->getListItems(),
            'ctas' => $this->getCtas(),
            'media_type' => $this->getMediaType(),
            'image' => $this->getImage(),
            'video' => $this->getVideo(),
            'lottie' => $this->getLottie(),
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
        $contentMedia = Builder::make('content_media');

        $contentMedia
            ->addMessage('Content Media', 'A split layout with content on the left and media on the right.')

            ->addTab('Content', [
                'placement' => 'top',
            ])
                // Section Heading at the top
                ->addPartial(SectionHeading::class)

                // Left side content
                ->addGroup('left_content', [
                    'label' => 'Left Side - Content',
                    'layout' => 'block',
                ])
                    ->addText('content_heading', [
                        'label' => 'Heading',
                        'instructions' => 'Main heading for the content area',
                        'required' => 0,
                    ])
                    ->addWysiwyg('content_text', [
                        'label' => 'Rich Text Content',
                        'instructions' => 'Main content/description',
                        'required' => 0,
                        'tabs' => 'visual',
                        'toolbar' => 'full',
                        'media_upload' => 1,
                    ])
                    ->addRepeater('list_items', [
                        'label' => 'List Items',
                        'instructions' => 'Add list items with icon and text',
                        'min' => 0,
                        'max' => 10,
                        'layout' => 'block',
                        'button_label' => 'Add List Item',
                    ])
                        ->addText('list_item_text', [
                            'label' => 'Text',
                            'instructions' => 'List item text',
                            'required' => 1,
                        ])
                    ->endRepeater()
                    ->addRepeater('ctas', [
                        'label' => 'Call to Actions',
                        'instructions' => 'Add one or more call to action buttons',
                        'min' => 0,
                        'max' => 2,
                        'layout' => 'block',
                        'button_label' => 'Add CTA',
                    ])
                        ->addLink('cta', [
                            'label' => 'Button',
                            'return_format' => 'array',
                        ])
                    ->endRepeater()
                ->endGroup()

                // Right side media
                ->addGroup('right_media', [
                    'label' => 'Right Side - Media',
                    'layout' => 'block',
                ])
                    ->addPartial(MediaComponent::class)
                ->endGroup()

            ->addTab('Settings', [
                'placement' => 'top',
            ])
                ->addPartial(SectionOptions::class);

        return $contentMedia->build();
    }

    /**
     * Return the section eyebrow field.
     *
     * @return string
     */
    public function getSectionEyebrow()
    {
        return get_field('eyebrow') ?: '';
    }

    /**
     * Return the section heading field.
     *
     * @return string
     */
    public function getSectionHeading()
    {
        return get_field('heading') ?: '';
    }

    /**
     * Return the section subtitle field.
     *
     * @return string
     */
    public function getSectionSubtitle()
    {
        return get_field('subtitle') ?: '';
    }

    /**
     * Return the content heading field.
     *
     * @return string
     */
    public function getContentHeading()
    {
        return get_field('left_content_content_heading') ?: '';
    }

    /**
     * Return the content text field.
     *
     * @return string
     */
    public function getContentText()
    {
        return get_field('left_content_content_text') ?: '';
    }

    /**
     * Return the list items field.
     *
     * @return array
     */
    public function getListItems()
    {
        return get_field('left_content_list_items') ?: [];
    }

    /**
     * Return the CTA fields.
     *
     * @return array
     */
    public function getCtas()
    {
        return get_field('left_content_ctas') ?: [];
    }

    /**
     * Return the media type field.
     *
     * @return string
     */
    public function getMediaType()
    {
        return get_field('right_media_media_type') ?: 'image';
    }

    /**
     * Return the image field.
     *
     * @return array|null
     */
    public function getImage()
    {
        return get_field('right_media_image');
    }

    /**
     * Return the video field.
     *
     * @return array|null
     */
    public function getVideo()
    {
        return get_field('right_media_video');
    }

    /**
     * Return the lottie field.
     *
     * @return array|null
     */
    public function getLottie()
    {
        return get_field('right_media_lottie');
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
     * Return the arch position field.
     *
     * @return string
     */
    public function getArchPosition()
    {
        return get_field('arch_position') ?: 'none';
    }
}
