<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Post Types
    |--------------------------------------------------------------------------
    |
    | Here you may configure the post types to be registered by Poet using
    | Extended CPTs. <https://github.com/johnbillion/extended-cpts/wiki>
    |
    */

    'post' => [
        'testimonial' => [
            'enter_title_here' => 'Enter testimonial title',
            'menu_icon' => 'dashicons-testimonial',
            'supports' => ['title', 'editor', 'revisions', 'thumbnail'],
            'show_in_rest' => true,
            'has_archive' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'labels' => [
                'singular' => 'Testimonial',
                'plural' => 'Testimonials',
            ],
            'admin_cols' => [
                'company_name' => [
                    'title' => 'Company',
                    'meta_key' => 'company_name',
                ],
                'client_name' => [
                    'title' => 'Client',
                    'meta_key' => 'client_name',
                ],
                'date' => [
                    'title' => 'Date',
                    'default' => 'ASC',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Taxonomies
    |--------------------------------------------------------------------------
    |
    | Here you may configure the taxonomies to be registered by Poet using
    | Extended CPTs. <https://github.com/johnbillion/extended-cpts/wiki>
    |
    */

    'taxonomy' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Blocks
    |--------------------------------------------------------------------------
    |
    | Here you may configure the Gutenberg blocks to be registered by Poet
    | and optionally set their corresponding Blade views.
    |
    */

    'block' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Block Categories
    |--------------------------------------------------------------------------
    |
    | Here you may configure the Gutenberg block categories to be registered
    | by Poet and optionally set their icons and colors.
    |
    */

    'block_category' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Block Patterns
    |--------------------------------------------------------------------------
    |
    | Here you may configure the Gutenberg block patterns to be registered
    | by Poet and optionally set their corresponding Blade views.
    |
    */

    'block_pattern' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Block Pattern Categories
    |--------------------------------------------------------------------------
    |
    | Here you may configure the Gutenberg block pattern categories to be
    | registered by Poet.
    |
    */

    'block_pattern_category' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Editor Color Palette
    |--------------------------------------------------------------------------
    |
    | Here you may configure the color palette used by the Gutenberg editor.
    | A corresponding editor-color-palette.json file must be present in your
    | theme for the editor to recognize the colors.
    |
    */

    'palette' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    |
    | Here you may configure admin settings for Poet including registering
    | menu pages and moving pages to a submenu.
    |
    */

    'admin' => [
        //
    ],

];
