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
        'case-study' => [
            'enter_title_here' => 'Enter case study title',
            'menu_icon' => 'dashicons-portfolio',
            'supports' => ['title', 'editor', 'revisions', 'thumbnail', 'excerpt'],
            'show_in_rest' => true,
            'has_archive' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'labels' => [
                'singular' => 'Case Study',
                'plural' => 'Case Studies',
            ],
            'rewrite' => [
                'slug' => 'case-studies',
            ],
            'admin_cols' => [
                'case_study_category' => [
                    'taxonomy' => 'case_study_category',
                ],
                'featured_image' => [
                    'title' => 'Hero Image',
                    'featured_image' => 'thumbnail',
                ],
                'date' => [
                    'title' => 'Date',
                    'default' => 'DESC',
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
            'links' => ['company'],
            'labels' => [
                'singular' => 'Country',
                'plural' => 'Countries',
                'menu_name' => 'Countries',
                'all_items' => 'All Countries',
                'add_new_item' => 'Add New Country',
                'edit_item' => 'Edit Country',
            ],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'show_in_quick_edit' => true,
            'show_tagcloud' => false,
            'meta_box_cb' => false,
        ],
        'company_category' => [
            'links' => ['company'],
            'labels' => [
                'singular' => 'Category',
                'plural' => 'Categories',
                'menu_name' => 'Categories',
                'all_items' => 'All Categories',
                'add_new_item' => 'Add New Category',
                'edit_item' => 'Edit Category',
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'show_in_quick_edit' => true,
            'show_tagcloud' => true,
            'meta_box_cb' => false,
        ],
        'case_study_category' => [
            'links' => ['case-study'],
            'labels' => [
                'singular' => 'Case Study Category',
                'plural' => 'Case Study Categories',
                'menu_name' => 'Categories',
                'all_items' => 'All Categories',
                'add_new_item' => 'Add New Category',
                'edit_item' => 'Edit Category',
            ],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'show_in_quick_edit' => true,
            'show_tagcloud' => false,
            'meta_box_cb' => false,
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
