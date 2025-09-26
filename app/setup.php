<?php

/**
 * Theme setup.
 */

namespace App;

use Illuminate\Support\Facades\Vite;

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => Vite::isRunningHot()
            ? "@import url('{$style}')"
            : Vite::content('resources/css/editor.css'),
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_filter('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    $dependencies = json_decode(Vite::content('editor.deps.json'));

    foreach ($dependencies as $dependency) {
        if (! wp_script_is($dependency)) {
            wp_enqueue_script($dependency);
        }
    }

    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Add Vite's HMR client to the block editor.
 *
 * @return void
 */
add_action('enqueue_block_assets', function () {
    if (! is_admin() || ! get_current_screen()?->is_block_editor()) {
        return;
    }

    if (! Vite::isRunningHot()) {
        return;
    }

    $script = sprintf(
        <<<'JS'
        window.__vite_client_url = '%s';

        window.self !== window.top && document.head.appendChild(
            Object.assign(document.createElement('script'), { type: 'module', src: '%s' })
        );
        JS
        ,
        untrailingslashit(Vite::asset('')),
        Vite::asset('@vite/client')
    );

    wp_add_inline_script('wp-blocks', $script);
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

    // Create Company Directory Facets programmatically
    add_action('wp_loaded', function () {
        // Check if facets already exist to avoid duplicates
        $existing_facets = FWP()->helper->get_facets();
        $facet_names = array_column($existing_facets, 'name');

        // Company Country Facet
        if (!in_array('company_country', $facet_names)) {
            FWP()->helper->save_facet([
                'name' => 'company_country',
                'label' => 'Country',
                'type' => 'dropdown',
                'source' => 'tax/company_country',
                'source_other' => '',
                'parent_term' => '',
                'modifier_type' => '',
                'modifier_values' => '',
                'ghost' => 'no',
                'auto_refresh' => 'yes',
                'search_engine' => 'no',
                'preserve_ghosts' => 'no',
                'operator' => 'or',
                'orderby' => 'display_value',
                'count' => '5',
                'soft_limit' => '0',
                'hierarchical' => 'no',
                'show_expanded' => 'no',
                'prefix' => '',
                'suffix' => '',
                'sort' => 'default'
            ]);
        }

        // Company Category Facet
        if (!in_array('company_category', $facet_names)) {
            FWP()->helper->save_facet([
                'name' => 'company_category',
                'label' => 'Category',
                'type' => 'dropdown',
                'source' => 'tax/company_category',
                'source_other' => '',
                'parent_term' => '',
                'modifier_type' => '',
                'modifier_values' => '',
                'ghost' => 'no',
                'auto_refresh' => 'yes',
                'search_engine' => 'no',
                'preserve_ghosts' => 'no',
                'operator' => 'or',
                'orderby' => 'display_value',
                'count' => '5',
                'soft_limit' => '0',
                'hierarchical' => 'yes',
                'show_expanded' => 'no',
                'prefix' => '',
                'suffix' => '',
                'sort' => 'default'
            ]);
        }

        // Company Search Facet
        if (!in_array('company_search', $facet_names)) {
            FWP()->helper->save_facet([
                'name' => 'company_search',
                'label' => 'Search',
                'type' => 'search',
                'source' => 'post_title',
                'source_other' => '',
                'parent_term' => '',
                'modifier_type' => '',
                'modifier_values' => '',
                'ghost' => 'no',
                'auto_refresh' => 'yes',
                'search_engine' => 'no',
                'preserve_ghosts' => 'no',
                'operator' => 'or',
                'orderby' => 'display_value',
                'count' => '5',
                'soft_limit' => '0',
                'hierarchical' => 'no',
                'show_expanded' => 'no',
                'prefix' => '',
                'suffix' => '',
                'sort' => 'default',
                'placeholder' => 'Search by company name...'
            ]);
        }

        // Create template if it doesn't exist
        $existing_templates = FWP()->helper->get_templates();
        $template_names = array_column($existing_templates, 'name');

        if (!in_array('company_directory', $template_names)) {
            FWP()->helper->save_template([
                'name' => 'company_directory',
                'label' => 'Company Directory',
                'query' => [
                    'post_type' => ['company'],
                    'post_status' => 'publish',
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'posts_per_page' => 50, // Pagination for better performance
                ],
                'layout' => 'custom'
            ]);
        }
    }, 99);
});

/**
 * Modify FacetWP query for company directory
 *
 * @param $query_args
 * @param $class
 * @return mixed
 */
add_filter('facetwp_query_args', function ($query_args, $class) {
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
}, 10, 2);

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

/**
 * Register custom FacetWP template for company directory
 *
 * @param $output
 * @param $params
 * @return string
 */
add_filter('facetwp_template_html', function ($output, $params) {
    if ('company_directory' === $params['template_name']) {
        // Start output buffering
        ob_start();

        // Set theme context for the template
        $theme = get_query_var('company_directory_theme', 'light');
        set_query_var('company_directory_theme', $theme);

        // Include the custom template
        $template_path = get_template_directory() . '/resources/views/facetwp/company-directory-template.php';
        if (file_exists($template_path)) {
            include $template_path;
        } else {
            echo '<p>Company directory template not found.</p>';
        }

        $output = ob_get_clean();
    }

    return $output;
}, 10, 2);

/**
 * Set theme context for FacetWP company directory
 * This allows the template to access the theme setting
 */
add_action('wp_head', function () {
    if (is_admin()) {
        return;
    }

    // Check if we're on a page with the company directory block
    global $post;
    if ($post && has_blocks($post->post_content)) {
        $blocks = parse_blocks($post->post_content);
        foreach ($blocks as $block) {
            if (isset($block['blockName']) && $block['blockName'] === 'acf/company-directory') {
                // Extract theme from block attributes if available
                $theme = $block['attrs']['data']['theme'] ?? 'light';
                set_query_var('company_directory_theme', $theme);
                break;
            }
        }
    }
});

/**
 * Ensure FacetWP loads only company posts for company directory
 *
 * @param $wp_query
 * @param $facet
 */
add_action('facetwp_pre_load', function ($params, $facet) {
    // Only modify query for company-related facets
    if (in_array($facet['name'], ['company_country', 'company_category', 'company_search'])) {
        $params['query_args']['post_type'] = 'company';
    }
}, 10, 2);
