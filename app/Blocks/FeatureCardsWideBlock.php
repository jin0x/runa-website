<?php

namespace App\Blocks;

class FeatureCardsWideBlock extends FeatureCardsBlock
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Feature Cards (16:9)';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'Feature cards with 16:9 media ratio (shares fields with Feature Cards).';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'runa';

    /**
     * The block slug.
     *
     * @var string
     */
    public $slug = 'feature-cards-wide';

    /**
     * Reuse the same view as the base block.
     *
     * @var string
     */
    public $view = 'blocks.feature-cards';    

    /**
     * Enforce 16:9 ratio on card images.
     */
    public function getImageRatio()
    {
        return '16:9';
    }

    /**
     * Override field group key to avoid duplicate registration.
     */
    public function fields()
    {
        $fields = parent::fields();
        $fields['key'] = 'group_feature_cards_wide';

        return $fields;
    }
}
