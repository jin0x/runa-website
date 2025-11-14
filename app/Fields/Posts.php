<?php

namespace App\Fields;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Field;

class Posts extends Field
{
    /**
     * The field group name.
     *
     * @var string
     */
    public $name = 'Posts';

    /**
     * The field group title.
     *
     * @var string
     */
    public $title = 'Post Settings';

    /**
     * Compose the field group.
     *
     * @return array
     */
    public function fields()
    {
        $posts = Builder::make('post_settings');

        $posts
            ->setLocation('post_type', '==', 'post')
            ->setGroupConfig('position', 'side')
            ->setGroupConfig('style', 'seamless');

        $posts->addTrueFalse(RUNA_FEATURED_POST_META_KEY, [
            'label' => 'Featured Post',
            'instructions' => 'Toggle to show this post in the featured layout on the blog index.',
            'message' => 'Show as featured post',
            'default_value' => 0,
            'ui' => 1,
            'ui_on_text' => 'Yes',
            'ui_off_text' => 'No',
        ]);

        return $posts->build();
    }
}
