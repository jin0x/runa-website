<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class GuideSectionBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Guide Section';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A section for guides with table of contents text and rich content.';

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
    public $keywords = ['guide', 'section', 'content', 'toc'];

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
        'align' => false,
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
            'toc_text' => $this->getTocText(),
            'section_content' => $this->getSectionContent(),
            'anchor_id' => $this->getAnchorId(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $guideSection = Builder::make('guide_section');

        $guideSection
            ->addMessage('Guide Section', 'Add a section to your guide. This will automatically appear in the table of contents.')
            
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
            ]);

        return $guideSection->build();
    }

    /**
     * Return the TOC text field.
     *
     * @return string
     */
    public function getTocText()
    {
        return get_field('toc_text') ?: '';
    }

    /**
     * Return the section content field.
     *
     * @return string
     */
    public function getSectionContent()
    {
        return get_field('section_content') ?: '';
    }

    /**
     * Return the auto-generated anchor ID.
     *
     * @return string
     */
    public function getAnchorId()
    {
        $tocText = $this->getTocText();
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $tocText)));
    }
}