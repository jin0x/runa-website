<?php

namespace App\Blocks;

use App\Fields\Partials\MediaComponent;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class GuideBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Guide';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A comprehensive guide block with hero section and guide content sections.';

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
    public $icon = 'book';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['guide', 'hero', 'content', 'toc', 'sections'];

    /**
     * The block post type allow list.
     *
     * @var array
     */
    public $post_types = ['resource'];

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
        'multiple' => false,
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
            // Hero fields
            'hero_eyebrow' => $this->getHeroEyebrow(),
            'hero_title' => $this->getHeroTitle(),
            'hero_description' => $this->getHeroDescription(),
            'hero_ctas' => $this->getHeroCtas(),
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

            // Guide sections
            'guide_sections' => $this->getGuideSections(),
            'toc_items' => $this->getTocItems(),

            // Download link
            'download_link' => $this->getDownloadLink(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $guide = Builder::make('guide');

        $guide
            ->addTab('Hero Section', [
                'placement' => 'top',
            ])
            ->addMessage('hero_message', 'Configure the hero section that appears at the top of your guide.')
            ->addText('hero_eyebrow', [
                'label' => 'Eyebrow Text',
                'instructions' => 'Small text displayed above the title',
                'required' => 0,
            ])
            ->addText('hero_title', [
                'label' => 'Title',
                'instructions' => 'Main heading for the hero section',
                'required' => 1,
            ])
            ->addText('hero_description', [
                'label' => 'Subtitle',
                'instructions' => 'Subtitle for the hero section',
                'required' => 0,
            ])
            ->addRepeater('hero_ctas', [
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

            ->addTab('Hero Background', [
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
                'instructions' => 'Adjust how the background image is positioned',
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
                'instructions' => 'Controls the vertical fade of the overlay',
                'default_value' => 50,
                'min' => 0,
                'max' => 100,
                'step' => 5,
                'append' => '%',
                'wrapper' => [
                    'width' => '25',
                ],
            ])

            ->addTab('Hero Media', [
                'placement' => 'top',
            ])
            ->addPartial(MediaComponent::class)

            ->addTab('Guide Content', [
                'placement' => 'top',
            ])
            ->addMessage('content_message', 'Add sections to your guide. Each section will automatically appear in the table of contents.')
            ->addRepeater('guide_sections', [
                'label' => 'Guide Sections',
                'instructions' => 'Add sections to your guide',
                'min' => 1,
                'layout' => 'block',
                'button_label' => 'Add Section',
            ])
                ->addText('toc_text', [
                    'label' => 'Table of Contents Text',
                    'instructions' => 'Short text for the table of contents (e.g., "Introduction", "Key Terms")',
                    'required' => 1,
                    'placeholder' => 'Section Name',
                ])
                ->addWysiwyg('section_content', [
                    'label' => 'Section Content',
                    'instructions' => 'The main content for this section',
                    'required' => 1,
                    'tabs' => 'visual,text',
                    'toolbar' => 'full',
                    'media_upload' => 1,
                ])
            ->endRepeater()

            ->addTab('Download', [
                'placement' => 'top',
            ])
            ->addUrl('download_link', [
                'label' => 'Download PDF Link',
                'instructions' => 'Link to downloadable PDF version of the guide (optional)',
                'required' => 0,
            ]);

        return $guide->build();
    }

    // Hero getters
    public function getHeroEyebrow()
    {
        return get_field('hero_eyebrow') ?: '';
    }

    public function getHeroTitle()
    {
        return get_field('hero_title') ?: '';
    }

    public function getHeroDescription()
    {
        return get_field('hero_description') ?: '';
    }

    public function getHeroCtas()
    {
        return get_field('hero_ctas') ?: [];
    }

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

    public function getBackgroundImage()
    {
        return get_field('background_image');
    }

    public function getBackgroundPosition()
    {
        return get_field('background_position') ?: 'center center';
    }

    public function getOverlayColor()
    {
        return get_field('overlay_color') ?: null;
    }

    public function getOverlayOpacity()
    {
        $opacity = get_field('overlay_opacity');
        if ($opacity === null) {
            return 0;
        }
        return max(0, min(100, intval($opacity)));
    }

    public function getContentWidth()
    {
        return get_field('content_width') ?: 'contained';
    }

    public function getCompact()
    {
        return get_field('compact') ?: false;
    }

    // Guide sections getters
    public function getGuideSections()
    {
        return get_field('guide_sections') ?: [];
    }

    public function getTocItems()
    {
        $sections = $this->getGuideSections();
        $tocItems = [];

        foreach ($sections as $section) {
            $tocText = $section['toc_text'] ?? '';
            if ($tocText) {
                $anchorId = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $tocText)));
                $tocItems[] = [
                    'text' => $tocText,
                    'anchor' => $anchorId
                ];
            }
        }

        return $tocItems;
    }

    public function getDownloadLink()
    {
        return get_field('download_link') ?: '';
    }
}
