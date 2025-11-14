<?php

/**
 * Theme setup.
 */

namespace App;

use function App\Helpers\enqueue_vite_assets;
use function App\Helpers\enqueue_editor_assets;
use function App\Helpers\get_vite_asset;

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
// add_filter('block_editor_settings_all', function ($settings) {
//     $style = Vite::asset('resources/css/editor.css');

//     $settings['styles'][] = [
//         'css' => Vite::isRunningHot()
//             ? "@import url('{$style}')"
//             : Vite::content('resources/css/editor.css'),
//     ];

//     return $settings;
// });

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_filter('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    enqueue_editor_assets();
});

/**
 * Fallback font loading for production environments
 * Ensures fonts load even if CSS compilation issues occur
 *
 * @return void
 */
add_action('wp_head', function () {
    echo "<style>
        @font-face {
            font-family: 'Lineca';
            font-display: swap;
            src: url('" . get_vite_asset('resources/fonts/Lineca-Regular.woff2') . "') format('woff2'),
                url('" . get_vite_asset('resources/fonts/Lineca-Regular.woff') . "') format('woff');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'Lineca';
            font-display: swap;
            src: url('" . get_vite_asset('resources/fonts/Lineca-Bold.woff2') . "') format('woff2'),
                url('" . get_vite_asset('resources/fonts/Lineca-Bold.woff') . "') format('woff');
            font-weight: 700;
            font-style: normal;
        }
    </style>";
}, 1); // Very high priority to load before other styles

/**
 * Enqueue theme assets using dynamic manifest.json lookup
 * Automatically handles hashed filenames from Vite builds
 *
 * @return void
 */
add_action('wp_enqueue_scripts', function () {
    enqueue_vite_assets();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', TEXT_DOMAIN),
        'footer_menu'        => __('Footer Menu', TEXT_DOMAIN),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');

    // Disable block-based widget editor to use legacy widgets
    remove_theme_support('widgets-block-editor');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    error_log('Footer widget areas being registered');
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ];

    // Footer widget areas
    $footer_widget_config = [
        'before_widget' => '<div class="footer-widget %1$s %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title uppercase text-primary-green-neon mb-4">',
        'after_title'   => '</h3>',
    ];

    register_sidebar([
                        'name' => __('Footer Widget Area 1', TEXT_DOMAIN),
                        'id'   => 'footer-widget-1',
                        'description' => __('First footer widget area', TEXT_DOMAIN),
                    ] + $footer_widget_config);

    register_sidebar([
                        'name' => __('Footer Widget Area 2', TEXT_DOMAIN),
                        'id'   => 'footer-widget-2',
                        'description' => __('Second footer widget area', TEXT_DOMAIN),
                    ] + $footer_widget_config);

    register_sidebar([
                        'name' => __('Footer Widget Area 3', TEXT_DOMAIN),
                        'id'   => 'footer-widget-3',
                        'description' => __('Third footer widget area', TEXT_DOMAIN),
                    ] + $footer_widget_config);

    register_sidebar([
                        'name' => __('Footer Widget Area 4', TEXT_DOMAIN),
                        'id'   => 'footer-widget-4',
                        'description' => __('Fourth footer widget area', TEXT_DOMAIN),
                    ] + $footer_widget_config);
});

/**
 * FacetWP Configuration for Company Directory
 *
 * @return void
 */
add_action('init', function () {
    if (!function_exists('FWP')) {
        return;
    }

    // Note: FacetWP facets and templates need to be created manually in the admin
    // This is because the FacetWP API doesn't expose public methods for programmatic creation
    // Visit WP Admin > Settings > FacetWP to create the following:

    /* Required Facets to Create Manually:
     *
     * 1. Country Facet:
     *    - Name: company_country
     *    - Label: Country
     *    - Type: Dropdown
     *    - Data Source: Taxonomy > company_country
     *
     * 2. Category Facet:
     *    - Name: company_category
     *    - Label: Category
     *    - Type: Dropdown
     *    - Data Source: Taxonomy > company_category
     *    - Enable Hierarchical: Yes
     *
     * 3. Search Facet:
     *    - Name: company_search
     *    - Label: Search
     *    - Type: Search
     *    - Data Source: Post Title
     *    - Placeholder: Search by company name...
     *
     * Note: No template needed - using custom WP_Query with 'facetwp' => true
     */
});

/**
 * Modify FacetWP query for company directory
 *
 * @param $query_args
 * @param $class
 * @return mixed
 */
add_filter('facetwp_query_args', function ($query_args) {
    // Only apply to company directory pages
    if (isset($query_args['post_type']) && in_array('company', (array) $query_args['post_type'])) {
        // Remove the limit from the original block query
        $query_args['posts_per_page'] = 50; // Start with reasonable pagination
        $query_args['meta_query'] = [
            [
                'key' => 'company_slug',
                'compare' => 'EXISTS',
            ],
        ];
    }

    return $query_args;
});

/**
 * Customize FacetWP settings for better performance
 *
 * @param $settings
 * @return mixed
 */
add_filter('facetwp_settings', function ($settings) {
    // Enable query caching for better performance
    $settings['cache'] = 'on';

    // Set reasonable pagination
    $settings['pager_default_per_page'] = 50;

    return $settings;
});

// Template-related code removed - now using custom WP_Query approach

// Theme context code removed - handled directly in Blade template now

/**
 * Ensure FacetWP loads only company posts for company directory
 *
 * @param $wp_query
 * @param $facet
 */
add_filter('facetwp_pre_load', function ($params, $facet) {
    // Only modify query for company-related facets
    if (isset($facet['name']) && in_array($facet['name'], ['company_country', 'company_category', 'company_search'])) {
        $params['query_args']['post_type'] = 'company';
    }
    return $params;
}, 10, 2);

/**
 * Unregister all core Gutenberg blocks.
 *
 * @return void
 */
add_action('allowed_block_types_all', function ($allowed_blocks, $editor_context) {
    // Get all registered blocks
    $registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();

    // Filter out core blocks (blocks that start with 'core/')
    $allowed_blocks = array_filter(
        array_keys($registered_blocks),
        function ($block_name) {
            return strpos($block_name, 'core/') !== 0;
        }
    );

    return array_values($allowed_blocks);
}, 10, 2);

/**
 * Keep featured posts out of the standard blog loop so they can be rendered separately.
 */
add_action('pre_get_posts', function ($query) {
    if (! defined('RUNA_FEATURED_POST_META_KEY')) {
        return;
    }

    if (is_admin() || ! $query->is_main_query() || ! $query->is_home()) {
        return;
    }

    $featuredFilter = [
        'relation' => 'OR',
        [
            'key'     => RUNA_FEATURED_POST_META_KEY,
            'compare' => 'NOT EXISTS',
        ],
        [
            'key'     => RUNA_FEATURED_POST_META_KEY,
            'value'   => '1',
            'compare' => '!=',
        ],
    ];

    $existingMetaQuery = $query->get('meta_query');

    if (! empty($existingMetaQuery)) {
        if (! isset($existingMetaQuery['relation'])) {
            $existingMetaQuery['relation'] = 'AND';
        }

        $existingMetaQuery[] = $featuredFilter;
        $query->set('meta_query', $existingMetaQuery);
        return;
    }

    $query->set('meta_query', $featuredFilter);
});
