<?php

namespace App\Blocks;

use App\Enums\ThemeVariant;
use App\Fields\Partials\MediaComponent;
use App\Fields\Partials\SectionHeading;
use App\Fields\Partials\SectionOptions;
use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class TabbedContentBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Tabbed Content';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A tabbed content section with text and media in each tab.';

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
    public $icon = 'table-row-after';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = ['tabs', 'content', 'media', 'tabbed'];

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
            'section_heading' => $this->getSectionHeading(),
            'tabs' => $this->getTabs(),
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
        $tabbedContent = Builder::make('tabbed_content');

        $tabbedContent
            ->addTab('Content', [
                'placement' => 'top',
            ])
            ->addGroup('section_heading', [
                'label' => 'Section Heading',
                'instructions' => 'Optional section heading above the tabs',
                'layout' => 'block',
            ])
            ->addPartial(SectionHeading::class)
            ->endGroup()
            ->addRepeater('tabs', [
                'label' => 'Tabs',
                'instructions' => 'Add tabs with content and media',
                'required' => 1,
                'min' => 1,
                'max' => 6,
                'layout' => 'block',
                'button_label' => 'Add Tab',
            ])
            ->addText('tab_label', [
                'label' => 'Tab Label',
                'instructions' => 'Label displayed in the tab navigation',
                'required' => 1,
                'wrapper' => [
                    'width' => '50',
                ],
            ])
            ->addText('tab_id', [
                'label' => 'Tab ID',
                'instructions' => 'Unique identifier for this tab (auto-generated if empty)',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
            ])
            ->addGroup('tab_content', [
                'label' => 'Tab Content',
                'layout' => 'block',
            ])
            ->addText('heading', [
                'label' => 'Heading',
                'instructions' => 'Content heading',
                'required' => 0,
            ])
            ->addTextarea('subtitle', [
                'label' => 'Subtitle',
                'instructions' => 'Content subtitle',
                'required' => 0,
                'rows' => 2,
            ])
            ->addWysiwyg('content', [
                'label' => 'Content',
                'instructions' => 'Main content text',
                'required' => 0,
                'tabs' => 'visual',
                'toolbar' => 'basic',
                'media_upload' => 0,
            ])
            ->addRepeater('ctas', [
                'label' => 'Call to Actions',
                'instructions' => 'Add call to action buttons',
                'min' => 0,
                'max' => 2,
                'layout' => 'table',
                'button_label' => 'Add Button',
            ])
            ->addLink('cta', [
                'label' => 'Button',
                'return_format' => 'array',
            ])
            ->endRepeater()
            ->endGroup()
            ->addGroup('tab_media', [
                'label' => 'Tab Media',
                'layout' => 'block',
            ])
            ->addPartial(MediaComponent::class)
            ->endGroup()
            ->endRepeater()

            ->addTab('Settings', [
                'placement' => 'top',
            ])
            ->addPartial(SectionOptions::withConfig([
                'themes' => [ThemeVariant::LIGHT, ThemeVariant::DARK]
            ]));

        return $tabbedContent->build();
    }

    /**
     * Return the section heading fields.
     *
     * @return array
     */
    public function getSectionHeading()
    {
        $heading = get_field('section_heading');
        return [
            'eyebrow' => $heading['eyebrow'] ?? '',
            'heading' => $heading['heading'] ?? '',
            'subtitle' => $heading['subtitle'] ?? '',
        ];
    }

    /**
     * Return the tabs with formatted data.
     *
     * @return array
     */
    public function getTabs()
    {
        $tabs = get_field('tabs') ?: [];
        $formattedTabs = [];

        foreach ($tabs as $index => $tab) {
            $tabId = !empty($tab['tab_id']) ? sanitize_title($tab['tab_id']) : 'tab-' . ($index + 1);

            $formattedTabs[] = [
                'id' => $tabId,
                'label' => $tab['tab_label'],
                'content_data' => [
                    'heading' => $tab['tab_content']['heading'] ?? '',
                    'subtitle' => $tab['tab_content']['subtitle'] ?? '',
                    'text' => $tab['tab_content']['content'] ?? '',
                    'ctas' => $tab['tab_content']['ctas'] ?? [],
                ],
                'media' => $this->formatMedia($tab['tab_media'] ?? []),
            ];
        }

        return $formattedTabs;
    }

    /**
     * Format media data for the component.
     *
     * @param array $media
     * @return array
     */
    private function formatMedia($media)
    {
        if (empty($media)) {
            return [];
        }

        $mediaType = $media['media_type'] ?? 'image';
        $mediaData = [
            'type' => $mediaType,
            'url' => '',
            'alt' => '',
        ];

        switch ($mediaType) {
            case 'image':
                if (!empty($media['image'])) {
                    $mediaData['url'] = $media['image']['url'] ?? '';
                    $mediaData['alt'] = $media['image']['alt'] ?? '';
                }
                break;
            case 'video':
                if (!empty($media['video'])) {
                    $mediaData['url'] = $media['video']['url'] ?? '';
                }
                break;
            case 'lottie':
                if (!empty($media['lottie'])) {
                    $mediaData['url'] = $media['lottie']['url'] ?? '';
                }
                break;
        }

        return $mediaData;
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
}
