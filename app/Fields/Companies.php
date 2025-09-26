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
            ->addText('company_currency', [
                'label' => 'Company Currency',
                'instructions' => 'Enter currency',
                'required' => 0,
                'placeholder' => 'e.g. EUR',
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
