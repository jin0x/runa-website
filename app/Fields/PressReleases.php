<?php

namespace App\Fields;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Field;

class PressReleases extends Field
{
    /**
     * The field group name.
     *
     * @var string
     */
    public $name = 'PressReleases';

    /**
     * The field group title.
     *
     * @var string
     */
    public $title = 'Press Release Settings';

    /**
     * Compose the field group.
     *
     * @return array
     */
    public function fields()
    {
        $pressReleases = Builder::make('press_release_settings');

        $pressReleases
            ->setLocation('post_type', '==', 'press_release')
            ->setGroupConfig('position', 'side')
            ->setGroupConfig('style', 'seamless');

        $pressReleases->addTrueFalse('runa_featured_press_release', [
            'label' => 'Featured Press Release',
            'instructions' => 'Toggle to show this press release in the featured layout on the press releases index.',
            'message' => 'Show as featured press release',
            'default_value' => 0,
            'ui' => 1,
            'ui_on_text' => 'Yes',
            'ui_off_text' => 'No',
        ]);

        return $pressReleases->build();
    }
}