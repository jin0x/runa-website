<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class SocialNetworks extends Partial
{
    /**
     * The partial field group.
     *
     * @return array
     */
    public function fields()
    {
        $socialNetworks = Builder::make('socialNetworks');

        $socialNetworks
            ->addRepeater('social_networks', [
                'label' => 'Social Networks',
                'instructions' => 'Add social media links',
                'button_label' => 'Add Social Network',
                'layout' => 'block',
            ])
            ->addSelect('network', [
                'label' => 'Network',
                'choices' => [
                    'github' => 'GitHub',
                    'twitter' => 'Twitter',
                    'linkedin' => 'LinkedIn',
                    'facebook' => 'Facebook',
                    'instagram' => 'Instagram',
                    'youtube' => 'YouTube',
                ],
                'ui' => 1,
                'wrapper' => [
                    'width' => '30',
                ],
            ])
            ->addUrl('url', [
                'label' => 'URL',
                'placeholder' => 'https://',
                'wrapper' => [
                    'width' => '70',
                ],
            ])
            ->endRepeater();

        return $socialNetworks;
    }
}
