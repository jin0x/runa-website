<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class SliderOptions extends Partial
{
    /**
     * The partial field group.
     *
     * @return \StoutLogic\AcfBuilder\FieldsBuilder
     */
    public function fields()
    {
        $sliderOptions = Builder::make('sliderOptions');

        $sliderOptions
            ->addTrueFalse('slider_navigation', [
                'label' => 'Show Navigation Arrows',
                'default_value' => 1,
                'ui' => 1,
                'wrapper' => [
                    'width' => '33',
                ],
            ])
            ->addTrueFalse('slider_pagination', [
                'label' => 'Show Pagination Dots',
                'default_value' => 1,
                'ui' => 1,
                'wrapper' => [
                    'width' => '33',
                ],
            ])
            ->addTrueFalse('slider_loop', [
                'label' => 'Enable Infinite Loop',
                'default_value' => 1,
                'ui' => 1,
                'wrapper' => [
                    'width' => '33',
                ],
            ])
            ->addNumber('slider_autoplay_delay', [
                'label' => 'Autoplay Delay (ms)',
                'instructions' => 'Time between slides in milliseconds (0 to disable autoplay)',
                'default_value' => 5000,
                'min' => 0,
                'max' => 10000,
                'step' => 500,
                'wrapper' => [
                    'width' => '50',
                ],
            ])
            ->addNumber('slider_space_between', [
                'label' => 'Space Between Slides (px)',
                'instructions' => 'Space between slides in pixels',
                'default_value' => 30,
                'min' => 0,
                'max' => 100,
                'wrapper' => [
                    'width' => '50',
                ],
            ])
            ->addNumber('slider_mobile_slides', [
                'label' => 'Mobile Slides Per View',
                'instructions' => 'Number of slides to show on mobile',
                'default_value' => 2,
                'min' => 1,
                'max' => 4,
                'wrapper' => [
                    'width' => '33',
                ],
            ])
            ->addNumber('slider_tablet_slides', [
                'label' => 'Tablet Slides Per View',
                'instructions' => 'Number of slides to show on tablet',
                'default_value' => 3,
                'min' => 1,
                'max' => 6,
                'wrapper' => [
                    'width' => '33',
                ],
            ])
            ->addNumber('slider_desktop_slides', [
                'label' => 'Desktop Slides Per View',
                'instructions' => 'Number of slides to show on desktop',
                'default_value' => 5,
                'min' => 1,
                'max' => 8,
                'wrapper' => [
                    'width' => '33',
                ],
            ]);

        return $sliderOptions;
    }
}
