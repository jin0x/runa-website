<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class CTALink extends Partial
{
    /**
     * The partial field group.
     *
     * @return \StoutLogic\AcfBuilder\FieldsBuilder
     */
    public function fields()
    {
        $cta = Builder::make('ctaLink')
                      ->addLink('cta', [
                          'label' => 'Call to Action',
                          'instructions' => 'Add a link for your call to action button',
                          'required' => 0,
                          'return_format' => 'array',
                      ]);

        return $cta;
    }
}
