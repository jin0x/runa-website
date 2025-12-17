<?php

namespace App\Fields;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Field;

class Resources extends Field
{
    /**
     * The field group name.
     *
     * @var string
     */
    public $name = 'Resources';

    /**
     * The field group title.
     *
     * @var string
     */
    public $title = 'Resource Settings';

    /**
     * Compose the field group.
     *
     * @return array
     */
    public function fields()
    {
        $resources = Builder::make('resource_settings');

        $resources
            ->setLocation('post_type', '==', 'resource')
            ->setGroupConfig('position', 'side')
            ->setGroupConfig('style', 'seamless');

        $resources->addTrueFalse('runa_featured_resource', [
            'label' => 'Featured Resource',
            'instructions' => 'Toggle to show this resource in the featured layout on the resources index.',
            'message' => 'Show as featured resource',
            'default_value' => 0,
            'ui' => 1,
            'ui_on_text' => 'Yes',
            'ui_off_text' => 'No',
        ]);

        // Guide-specific fields - only show for Guides category  
        $resources->addTab('Guide Settings', [
            'placement' => 'top',
        ])
        ->addUrl('guide_download_link', [
            'label' => 'Download PDF Link',
            'instructions' => 'Optional: Add a link to a downloadable PDF version of this guide',
            'required' => 0,
        ]);

        return $resources->build();
    }
}