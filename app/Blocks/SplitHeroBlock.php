<?php

namespace App\Blocks;

use App\Fields\Partials\MediaComponent;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class SplitHeroBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Split Hero';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A 50/50 split hero section with content and media side by side.';

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
    public $icon = 'columns';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['hero', 'split', 'banner', 'header'];

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
            'eyebrow' => $this->eyebrow(),
            'title' => $this->title(),
            'content' => $this->description(),
            'ctas' => $this->ctas(),
            'media_type' => $this->getMediaType(),
            'image' => $this->getImage(),
            'video' => $this->getVideo(),
            'lottie' => $this->getLottie(),
            'overlay_color' => $this->getOverlayColor(),
            'overlay_opacity' => $this->getOverlayOpacity(),
            'compact' => $this->getCompact(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $splitHero = Builder::make('split_hero');

        $splitHero
            ->addTab('Content', [
                'placement' => 'top',
            ])
            ->addText('eyebrow', [
                'label' => 'Eyebrow Text',
                'instructions' => 'Small text displayed above the title',
                'required' => 0,
            ])
            ->addText('title', [
                'label' => 'Title',
                'instructions' => 'Main heading for the hero section',
                'required' => 1,
            ])
            ->addText('description', [
                'label' => 'Subtitle',
                'instructions' => 'Subtitle for the hero section',
                'required' => 0,
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

            ->addTab('Media', [
                'placement' => 'top',
            ])
            ->addPartial(MediaComponent::class)

            ->addTab('Background Overlay', [
                'placement' => 'top',
            ])
            ->addColorPicker('overlay_color', [
                'label' => 'Overlay Color (Mobile only)',
                'default_value' => '#000000',
                'wrapper' => [
                    'width' => '25',
                ],
            ])
            ->addRange('overlay_opacity', [
                'label' => 'Overlay Opacity (Mobile only)',
                'instructions' => 'Controls the opacity of the overlay.',
                'default_value' => 50,
                'min' => 0,
                'max' => 100,
                'step' => 5,
                'append' => '%',
                'wrapper' => [
                    'width' => '25',
                ],
            ])

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addTrueFalse('compact', [
                'label' => 'Compact Layout',
                'instructions' => 'Enable for a shorter hero section (600px instead of full viewport)',
                'default_value' => 0,
                'ui' => 1,
            ]);

        return $splitHero->build();
    }

    /**
     * Return the eyebrow field.
     *
     * @return string
     */
    public function eyebrow()
    {
        return get_field('eyebrow');
    }

    /**
     * Return the title field.
     *
     * @return string
     */
    public function title()
    {
        return get_field('title');
    }

    /**
     * Return the content field.
     *
     * @return string
     */
    public function description()
    {
        return get_field('description');
    }

    /**
     * Return the CTA fields.
     *
     * @return array
     */
    public function ctas()
    {
        return get_field('ctas') ?: [];
    }

    /**
     * Get the media type.
     *
     * @return string
     */
    public function getMediaType()
    {
        return get_field('media_type') ?: 'image';
    }

    /**
     * Get the image field.
     *
     * @return array|null
     */
    public function getImage()
    {
        return get_field('image');
    }

    /**
     * Get the video field.
     *
     * @return array|null
     */
    public function getVideo()
    {
        return get_field('video');
    }

    /**
     * Get the lottie field.
     *
     * @return array|null
     */
    public function getLottie()
    {
        return get_field('lottie');
    }

    /**
     * Get the overlay color field.
     *
     * @return string|null
     */
    public function getOverlayColor()
    {
        return get_field('overlay_color') ?: null;
    }

    /**
     * Get the overlay opacity field (normalized to 0–1).
     *
     * @return float
     */
    public function getOverlayOpacity()
    {
        $opacity = get_field('overlay_opacity');
        if ($opacity === null) {
            return 0;
        }

        return max(0, min(100, intval($opacity)));
    }

    /**
     * Get the compact field.
     *
     * @return bool
     */
    public function getCompact()
    {
        return get_field('compact') ?: false;
    }
}