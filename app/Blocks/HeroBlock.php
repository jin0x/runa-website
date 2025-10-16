<?php

namespace App\Blocks;

use App\Fields\Partials\CTALink;
use App\Fields\Partials\MediaComponent;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class HeroBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Hero';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A Hero section with title, content, and CTA.';

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
    public $icon = 'format-image';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['hero', 'banner', 'header'];

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
            'background_image' => $this->getBackgroundImage(),
            'background_position' => $this->getBackgroundPosition(),
            'overlay_color' => $this->getOverlayColor(),
            'overlay_opacity' => $this->getOverlayOpacity(),
            'content_width' => $this->getContentWidth(),
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
        $hero = Builder::make('hero');

        $hero
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
            ->addSelect('content_width', [
                'label' => 'Content Width',
                'instructions' => 'Choose how wide the text content should be',
                'choices' => [
                    'contained' => 'Contained (within container)',
                    'full-width' => 'Full Width',
                ],
                'default_value' => 'contained',
                'ui' => 1,
                'wrapper' => [
                    'width' => '50',
                ],
            ])
            ->addTrueFalse('compact', [
                'label' => 'Compact Layout',
                'instructions' => 'Enable for a shorter hero section',
                'default_value' => 0,
                'ui' => 1,
            ])
            ->addTab('Background', [
                'placement' => 'top',
            ])
            ->addImage('background_image', [
                'label' => 'Background Image',
                'instructions' => 'Background image for the hero section',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => [
                    'width' => '25',
                ],
            ])
            ->addSelect('background_position', [
                'label' => 'Background Image Focus',
                'instructions' => 'Adjust how the background image is positioned within the hero.',
                'choices' => [
                    'top left' => 'Top Left',
                    'top center' => 'Top Center',
                    'top right' => 'Top Right',
                    'center left' => 'Center Left',
                    'center center' => 'Center',
                    'center right' => 'Center Right',
                    'bottom left' => 'Bottom Left',
                    'bottom center' => 'Bottom Center',
                    'bottom right' => 'Bottom Right',
                ],
                'default_value' => 'center center',
                'ui' => 1,
                'wrapper' => [
                    'width' => '25',
                ],
            ])
            ->addColorPicker('overlay_color', [
                'label' => 'Overlay Color',
                'default_value' => '#000000',
                'wrapper' => [
                    'width' => '25',
                ],
            ])
            ->addRange('overlay_opacity', [
                'label' => 'Overlay Opacity',
                'instructions' => 'Controls the vertical fade of the overlay. The value defines how far the gradient extends from solid color (0%) to transparent (100%).',
                'default_value' => 50,
                'min' => 0,
                'max' => 100,
                'step' => 5,
                'append' => '%',
                'wrapper' => [
                    'width' => '25',
                ],
            ])
            ->addTab('Media', [
                'placement' => 'top',
            ])
            ->addPartial(MediaComponent::class);

        return $hero->build();
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
     * Get the background image field.
     *
     * @return array|null
     */
    public function getBackgroundImage()
    {
        return get_field('background_image');
    }

    /**
     * Get the background position value.
     *
     * @return string
     */
    public function getBackgroundPosition()
    {
        return get_field('background_position') ?: 'center center';
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
     * Get the compact field.
     *
     * @return bool
     */
    public function getCompact()
    {
        return get_field('compact') ?: false;
    }

    /**
     * Return the content width field.
     *
     * @return string
     */
    public function getContentWidth()
    {
        return get_field('content_width') ?: 'contained';
    }
}
