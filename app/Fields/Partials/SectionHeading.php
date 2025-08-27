<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class SectionHeading extends Partial
{
    /**
     * The partial field group.
     *
     * @return \StoutLogic\AcfBuilder\FieldsBuilder
     */
    public function fields()
    {
        $sectionHeading = Builder::make('sectionHeading');

        $sectionHeading
            ->addText('eyebrow', [
                'label' => 'Eyebrow',
                'instructions' => 'Small text displayed above the heading',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
            ])
            ->addText('heading', [
                'label' => 'Heading',
                'instructions' => 'Main heading for the section',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
            ])
            ->addTextarea('subtitle', [
                'label' => 'Subtitle',
                'instructions' => 'Descriptive text displayed below the heading',
                'required' => 0,
                'rows' => 3,
            ]);

        return $sectionHeading;
    }
}
