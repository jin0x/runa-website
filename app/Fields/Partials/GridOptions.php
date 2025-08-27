<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class GridOptions extends Partial
{
    /**
     * The partial field group.
     *
     * @return \StoutLogic\AcfBuilder\FieldsBuilder
     */
    public function fields()
    {
        $gridOptions = Builder::make('gridOptions');

        $gridOptions
            ->addSelect('grid_columns', [
                'label' => 'Grid Columns',
                'instructions' => 'Number of columns to display',
                'choices' => [
                    '2' => '2 Columns',
                    '3' => '3 Columns',
                    '4' => '4 Columns',
                    '6' => '6 Columns',
                ],
                'default_value' => '4',
                'wrapper' => [
                    'width' => '50',
                ],
                'return_format' => 'value', // Store value only, not label
            ])
            ->addSelect('grid_gap', [
                'label' => 'Grid Gap',
                'instructions' => 'Space between grid items',
                'choices' => [
                    'xs' => 'Extra Small',
                    'sm' => 'Small',
                    'md' => 'Medium',
                    'lg' => 'Large',
                    'xl' => 'Extra Large',
                ],
                'default_value' => 'lg',
                'wrapper' => [
                    'width' => '50',
                ],
            ]);

        return $gridOptions;
    }
}
