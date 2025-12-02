<?php

namespace App\Fields\Partials;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Partial;

class MediaComponent extends Partial
{
    /**
     * The partial field group.
     *
     * @return \StoutLogic\AcfBuilder\FieldsBuilder
     */
    public function fields()
    {
        $media = Builder::make('mediaComponent');

        $media
            ->addSelect('media_type', [
                'label' => 'Media Type',
                'instructions' => 'Select the type of media to display',
                'choices' => [
                    'image' => 'Image',
                    'video' => 'Video',
                    'lottie' => 'Lottie Animation'
                ],
                'default_value' => 'image',
                'required' => 1,
                'wrapper' => [
                    'width' => '50'
                ]
            ])
            ->addImage('image', [
                'label' => 'Image',
                'instructions' => 'Select an image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'media_type',
                            'operator' => '==',
                            'value' => 'image',
                        ],
                    ],
                ],
            ])
            ->addFile('video', [
                'label' => 'Video File',
                'instructions' => 'Upload or select a video file (MP4)',
                'return_format' => 'array',
                'library' => 'all',
                'mime_types' => 'mp4',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'media_type',
                            'operator' => '==',
                            'value' => 'video',
                        ],
                    ],
                ],
            ])
            ->addFile('lottie', [
                'label' => 'Lottie Animation',
                'instructions' => 'Upload or select a Lottie JSON file',
                'return_format' => 'array',
                'library' => 'all',
                'mime_types' => 'json',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'media_type',
                            'operator' => '==',
                            'value' => 'lottie',
                        ],
                    ],
                ],
            ]);

        return $media;
    }
}
