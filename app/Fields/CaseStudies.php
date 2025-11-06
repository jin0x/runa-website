<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use Log1x\AcfComposer\Builder;

class CaseStudies extends Field
{
    /**
     * The field group name.
     *
     * @var string
     */
    public $name = 'CaseStudies';

    /**
     * The field group title.
     *
     * @var string
     */
    public $title = 'Case Study Details';

    /**
     * The field group description.
     *
     * @var string
     */
    public $description = 'Add case study information and details';

    /**
     * The field group category.
     *
     * @var string
     */
    public $category = 'theme';

    /**
     * Compose the field group.
     *
     * @return array
     */
    public function fields()
    {
        $caseStudies = Builder::make('case_studies');

        $caseStudies
            ->setLocation('post_type', '==', 'case-study');

        $caseStudies
            // Hero Section
            ->addTab('hero_section', [
                'label' => 'Hero Section',
                'placement' => 'top',
            ])
            ->addTextarea('hero_description', [
                'label' => 'Hero Description/Subtitle',
                'instructions' => 'Enter the subtitle that appears below the main title',
                'rows' => 3,
                'placeholder' => 'e.g. With a double-digit lift in new signups...',
            ])
            ->addText('cta_text', [
                'label' => 'CTA Button Text',
                'instructions' => 'Text for the download button',
                'required' => 0,
                'default_value' => 'DOWNLOAD CASE STUDY',
                'placeholder' => 'DOWNLOAD CASE STUDY',
            ])
            ->addFile('case_study_pdf', [
                'label' => 'Case Study PDF',
                'instructions' => 'Upload the case study PDF file',
                'required' => 0,
                'return_format' => 'array',
                'library' => 'all',
                'mime_types' => 'pdf',
            ])
            ->addImage('hero_image', [
                'label' => 'Hero Image',
                'instructions' => 'Upload the hero/featured image',
                'required' => 0,
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ])

            // Company Information
            ->addTab('company_information', [
                'label' => 'Company Information',
                'placement' => 'top',
            ])
            ->addRepeater('case_study_left_content_items', [
                'label' => 'Left Content Items',
                'instructions' => 'Add here the main left-side case study content. This section typically includes company background or context, the challenge or opportunity, the approach or solution, and key narrative paragraphs.',
                'layout' => 'block',
                'button_label' => 'Add Content',
                'min' => 0,
                'max' => 10,
            ])
                ->addText('content_title', [
                    'label' => 'Heading',
                    'required' => 1,
                ])
                ->addWysiwyg('content_description', [
                    'label' => 'Content Description',
                    'tabs' => 'visual',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                ])
            ->endRepeater()
            ->addRepeater('case_study_bottom_content_items', [
                'label' => 'Bottom Content Items',
                'instructions' => 'Add here the bottom content of the case study, typically including summary of impact, key results, metrics, growth outcomes, or supporting performance data.',
                'layout' => 'block',
                'button_label' => 'Add Content',
                'min' => 0,
                'max' => 10,
            ])
                ->addText('content_title', [
                    'label' => 'Heading',
                    'required' => 1,
                ])
                ->addWysiwyg('content_description', [
                    'label' => 'Content Description',
                    'tabs' => 'visual',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                ])
            ->endRepeater()
            // Results Section
            ->addTab('results_section', [
                'label' => 'Results',
                'placement' => 'top',
            ])
            ->addMessage('Right Content', 'Add here the main right-side case study content.')
            ->addText('results_section_heading', [
                'label' => 'Heading',
                'instructions' => '"Results" as default',
            ])
            ->addRepeater('results', [
                'label' => 'Result Metrics',
                'instructions' => 'Add key result metrics (e.g., $11 million, 95%, etc.)',
                'required' => 0,
                'layout' => 'block',
                'button_label' => 'Add Result',
                'min' => 0,
                'max' => 10,
            ])
                ->addText('result_heading', [
                    'label' => 'Result Heading',
                    'instructions' => 'The main metric or number',
                    'required' => 1,
                    'placeholder' => 'e.g. $11 million, 95%, 17% lift',
                ])
                ->addText('result_description', [
                    'label' => 'Result Description',
                    'instructions' => 'Description of the metric',
                    'required' => 1,
                    'placeholder' => 'e.g. in transactions, improvement new campaign turnaround',
                ])
            ->endRepeater()

            // Testimonial Section
            ->addTab('testimonial_section', [
                'label' => 'Testimonial',
                'placement' => 'top',
            ])
            ->addPostObject('testimonial_reference', [
                'label' => 'Testimonial Reference',
                'instructions' => 'Select a testimonial to display in this case study',
                'required' => 0,
                'post_type' => ['testimonial'],
                'allow_null' => 1,
                'multiple' => 0,
                'return_format' => 'object',
            ])

            // Success Points
            ->addTab('success_points', [
                'label' => 'Success Points',
                'placement' => 'top',
            ])
            ->addText('success_points_section_heading', [
                'label' => 'Heading',
                'instructions' => '"Why partners trust Runa for long-term growth" as default',
            ])
            ->addRepeater('success_points', [
                'label' => 'Success in Numbers',
                'instructions' => 'Add bullet points highlighting key success metrics',
                'required' => 0,
                'layout' => 'block',
                'button_label' => 'Add Success Point',
                'min' => 0,
                'max' => 10,
            ])
                ->addTextarea('success_point_text', [
                    'label' => 'Success Point',
                    'instructions' => 'Enter a success point or achievement',
                    'required' => 1,
                    'rows' => 3,
                    'placeholder' => 'e.g. Vodafone has seen many gains as a result of the solution.',
                ])
            ->endRepeater();

        return $caseStudies->build();
    }

}
