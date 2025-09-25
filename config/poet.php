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
        'company' => [
            'enter_title_here' => 'Enter company name',
            'menu_icon' => 'dashicons-building',
            'supports' => ['title', 'revisions', 'thumbnail'],
            'show_in_rest' => true,
            'has_archive' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'labels' => [
                'singular' => 'Company',
                'plural' => 'Companies',
            ],
            'admin_cols' => [
                'company_slug' => [
                    'title' => 'Slug',
                    'meta_key' => 'company_slug',
                ],
                'country_name' => [
                    'title' => 'Country',
                    'meta_key' => 'country_name',
                ],
                'company_country' => [
                    'taxonomy' => 'company_country',
                ],
                'company_category' => [
                    'taxonomy' => 'company_category',
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
        'company_country' => [
            'post_type' => ['company'],
            'labels' => [
                'singular' => 'Country',
                'plural' => 'Countries',
            ],
            'hierarchical' => false,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'show_in_quick_edit' => true,
            'show_tagcloud' => false,
        ],
        'company_category' => [
            'post_type' => ['company'],
            'labels' => [
                'singular' => 'Category',
                'plural' => 'Categories',
            ],
            'hierarchical' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'show_in_quick_edit' => true,
            'show_tagcloud' => true,
        ],
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
