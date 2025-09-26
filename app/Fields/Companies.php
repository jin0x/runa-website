<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use Log1x\AcfComposer\Builder;

class Companies extends Field
{
    /**
     * The field group name.
     *
     * @var string
     */
    public $name = 'Companies';

    /**
     * The field group title.
     *
     * @var string
     */
    public $title = 'Company Details';

    /**
     * The field group description.
     *
     * @var string
     */
    public $description = 'Add company information and details';

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
        $companies = Builder::make('companies');

        $companies
            ->setLocation('post_type', '==', 'company');

        $companies
            ->addText('company_slug', [
                'label' => 'Company Slug/Code',
                'instructions' => 'Enter the unique company code or slug',
                'required' => 1,
                'placeholder' => 'e.g. 47ST-AR',
            ])
            ->addText('country_code', [
                'label' => 'Country Code',
                'instructions' => 'Enter the 2-letter country code',
                'required' => 1,
                'placeholder' => 'e.g. AR, AT, US',
                'maxlength' => 2,
            ])
            ->addText('country_name', [
                'label' => 'Country Name',
                'instructions' => 'Enter the full country name',
                'required' => 1,
                'placeholder' => 'e.g. Argentina, Austria',
            ])
            ->addUrl('image_url', [
                'label' => 'Company Logo URL',
                'instructions' => 'Enter the company logo URL (optional)',
                'required' => 0,
                'placeholder' => 'https://example.com/logo.png',
            ]);

        return $companies->build();
    }

}