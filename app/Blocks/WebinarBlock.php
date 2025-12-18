<?php

namespace App\Blocks;

use App\Fields\Partials\MediaComponent;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class WebinarBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Webinar';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A specialized block for webinar content with video, presenters, and newsletter signup.';

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
    public $icon = 'video-alt2';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['webinar', 'video', 'presenter', 'event'];

    /**
     * The block post type allow list.
     *
     * @var array
     */
    public $post_types = ['resource'];

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
            'badge' => $this->getBadge(),
            'title' => $this->getTitle(),
            'list_items' => $this->getListItems(),
            'media_type' => $this->getMediaType(),
            'image' => $this->getImage(),
            'video' => $this->getVideo(),
            'presenters' => $this->getPresenters(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $webinar = Builder::make('webinar');

        $webinar
            ->addMessage('Webinar Block', 'A specialized layout for webinar content with video, presenters list, and newsletter signup.')

            ->addTab('Content', [
                'placement' => 'top',
            ])
                ->addText('badge', [
                    'label' => 'Badge',
                    'instructions' => 'Badge text (e.g., "WEBINAR")',
                    'required' => 0,
                ])
                ->addText('title', [
                    'label' => 'Title',
                    'instructions' => 'Main webinar title',
                    'required' => 1,
                ])
                ->addRepeater('list_items', [
                    'label' => 'Content List',
                    'instructions' => 'Add list items with timestamps or bullet points',
                    'min' => 0,
                    'max' => 20,
                    'layout' => 'block',
                    'button_label' => 'Add List Item',
                ])
                    ->addText('list_item_text', [
                        'label' => 'Text',
                        'instructions' => 'List item text (can include timestamp)',
                        'required' => 1,
                    ])
                ->endRepeater()

            ->addTab('Media', [
                'placement' => 'top',
            ])
                ->addPartial(MediaComponent::class)

            ->addTab('Presenters', [
                'placement' => 'top',
            ])
                ->addRepeater('presenters', [
                    'label' => 'Presenters',
                    'instructions' => 'Add webinar presenters',
                    'min' => 0,
                    'max' => 10,
                    'layout' => 'block',
                    'button_label' => 'Add Presenter',
                ])
                    ->addText('presenter_name', [
                        'label' => 'Name',
                        'instructions' => 'Presenter full name',
                        'required' => 1,
                    ])
                    ->addText('presenter_position', [
                        'label' => 'Position',
                        'instructions' => 'Job title and/or company',
                        'required' => 0,
                    ])
                    ->addImage('presenter_image', [
                        'label' => 'Photo',
                        'instructions' => 'Presenter headshot',
                        'required' => 0,
                        'return_format' => 'array',
                    ])
                ->endRepeater();

        return $webinar->build();
    }

    /**
     * Return the badge field.
     *
     * @return string
     */
    public function getBadge()
    {
        return get_field('badge') ?: '';
    }

    /**
     * Return the title field.
     *
     * @return string
     */
    public function getTitle()
    {
        return get_field('title') ?: '';
    }

    /**
     * Return the list items field.
     *
     * @return array
     */
    public function getListItems()
    {
        return get_field('list_items') ?: [];
    }

    /**
     * Return the media type field.
     *
     * @return string
     */
    public function getMediaType()
    {
        return get_field('media_type') ?: 'image';
    }

    /**
     * Return the image field.
     *
     * @return array|null
     */
    public function getImage()
    {
        return get_field('image');
    }

    /**
     * Return the video field.
     *
     * @return array|null
     */
    public function getVideo()
    {
        return get_field('video');
    }

    /**
     * Return the presenters field.
     *
     * @return array
     */
    public function getPresenters()
    {
        return get_field('presenters') ?: [];
    }
}
