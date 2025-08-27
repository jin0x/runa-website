<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class SectionOptions extends Partial
{
    /**
     * The partial field group.
     *
     * @return \StoutLogic\AcfBuilder\FieldsBuilder
     */
    public function fields()
    {
        $sectionOptions = Builder::make('sectionOptions');

        $sectionOptions
            ->addSelect('section_size', [
                'label' => 'Section Size',
                'instructions' => 'Choose the vertical padding for this section',
                'choices' => [
                    'none' => 'None (No Padding)',
                    'xs' => 'Extra Small',
                    'sm' => 'Small',
                    'md' => 'Medium (Default)',
                    'lg' => 'Large',
                    'xl' => 'Extra Large',
                ],
                'default_value' => 'md',
                'wrapper' => [
                    'width' => '50',
                ],
            ])
            ->addSelect('theme', [
                'label' => 'Section Theme',
                'instructions' => 'Choose the color theme for this section',
                'choices' => [
                    'light' => 'Light',
                    'dark' => 'Dark',
                ],
                'default_value' => 'light',
                'wrapper' => [
                    'width' => '50',
                ],
            ]);

        return $sectionOptions;
    }
}
