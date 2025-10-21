<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use Log1x\AcfComposer\Builder;

class Testimonials extends Field
{
    /**
     * The field group name.
     *
     * @var string
     */
    public $name = 'Testimonials';

    /**
     * The field group title.
     *
     * @var string
     */
    public $title = 'Testimonial Details';

    /**
     * The field group description.
     *
     * @var string
     */
    public $description = 'Add testimonial information and details';

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
        $testimonials = Builder::make('testimonials');

        $testimonials
            ->setLocation('post_type', '==', 'testimonial');

        $testimonials
            ->addText('company_name', [
                'label' => 'Company Name',
                'instructions' => 'Enter the company or organization name',
                'required' => 1,
                'placeholder' => 'e.g. Acme Corporation',
            ])
            ->addText('client_name', [
                'label' => 'Client Name',
                'instructions' => 'Enter the name of the person giving the testimonial',
                'required' => 1,
                'placeholder' => 'e.g. John Smith',
            ])
            ->addText('client_position', [
                'label' => 'Position/Title',
                'instructions' => 'Enter the client\'s job title or position',
                'required' => 0,
                'placeholder' => 'e.g. CEO, Marketing Director',
            ])
            ->addTextarea('quote', [
                'label' => 'Testimonial Quote',
                'instructions' => 'Enter the main testimonial content',
                'required' => 1,
                'rows' => 6,
                'placeholder' => 'Enter the testimonial quote here...',
                'new_lines' => 'br',
            ])
            ->addImage('company_logo', [
                'label' => 'Company Logo',
                'instructions' => 'Upload the company logo (optional)',
                'required' => 0,
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ])
            ->addUrl('website_url', [
                'label' => 'Website URL',
                'instructions' => 'Enter the company website URL (optional)',
                'required' => 0,
                'placeholder' => 'https://example.com',
            ])
            ->addUrl('testimonial_url', [
                'label' => 'Testimonial URL',
                'instructions' => 'Enter the full testimonial URL (optional)',
                'required' => 0,
                'placeholder' => 'https://example.com',
            ])
            ->addNumber('rating', [
                'label' => 'Rating',
                'instructions' => 'Optional rating out of 5 stars',
                'required' => 0,
                'min' => 1,
                'max' => 5,
                'step' => 1,
                'placeholder' => '5',
            ])
            ->addDatePicker('testimonial_date', [
                'label' => 'Testimonial Date',
                'instructions' => 'Date when the testimonial was given (optional)',
                'required' => 0,
                'display_format' => 'F j, Y',
                'return_format' => 'Y-m-d',
                'first_day' => 1,
            ]);

        return $testimonials->build();
    }

}