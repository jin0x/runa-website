<?php

/**
 * Theme helpers.
 */

namespace App\Helpers;

/**
 * Get asset URL from manifest.json
 * Bypass for Acorn beta Vite::asset() bug
 *
 * @param string $resource_path The resource path (e.g., 'resources/css/app.css')
 * @return string The asset URL or empty string if not found
 */
function get_vite_asset($resource_path)
{
    static $manifest = null;

    // Defensive check for WordPress functions
    if (!function_exists('get_template_directory')) {
        return '';
    }

    // Load manifest once per request
    if ($manifest === null) {
        $manifest_path = get_template_directory() . '/public/build/manifest.json';
        $manifest = file_exists($manifest_path)
            ? json_decode(file_get_contents($manifest_path), true)
            : [];
    }

    // Return hashed filename from manifest
    if (isset($manifest[$resource_path]['file'])) {
        return get_template_directory_uri() . '/public/build/' . $manifest[$resource_path]['file'];
    }

    // Fallback to direct path (for development or missing assets)
    return get_template_directory_uri() . '/public/build/' . ltrim($resource_path, '/');
}

/**
 * Get entry point with associated CSS chunks
 * Critical for JavaScript entries that generate CSS
 *
 * @param string $entry_path The entry path (e.g., 'resources/js/app.js')
 * @return array Array with 'js' and 'css' keys
 */
function get_vite_entry_with_css($entry_path)
{
    static $manifest = null;

    // Defensive check for WordPress functions
    if (!function_exists('get_template_directory')) {
        return ['js' => '', 'css' => []];
    }

    // Load manifest once per request
    if ($manifest === null) {
        $manifest_path = get_template_directory() . '/public/build/manifest.json';
        $manifest = file_exists($manifest_path)
            ? json_decode(file_get_contents($manifest_path), true)
            : [];
    }

    $result = ['js' => '', 'css' => []];

    if (!isset($manifest[$entry_path])) {
        return $result;
    }

    $entry = $manifest[$entry_path];
    $base_url = get_template_directory_uri() . '/public/build/';

    // Get the main JavaScript file
    if (isset($entry['file'])) {
        $result['js'] = $base_url . $entry['file'];
    }

    // Get associated CSS chunks
    if (isset($entry['css']) && is_array($entry['css'])) {
        foreach ($entry['css'] as $css_file) {
            $result['css'][] = $base_url . $css_file;
        }
    }

    return $result;
}

/**
 * Applies specific Tailwind CSS classes to HTML tags within a given content string.
 *
 * This function modifies the provided content by adding predefined CSS classes to
 * unordered lists (`<ul>`), list items (`<li>`), paragraphs (`<p>`), and links (`<a>`) tags.
 * Additionally, it applies specific heading styles to `<h1>` through `<h6>` tags using a
 * `preg_replace_callback`.
 *
 * @param string $content The HTML content to modify.
 *
 * @return string The formatted HTML content with the applied styles.
 */

function apply_tailwind_classes_to_content(string $content, array $options = []): string
{
    if (empty($content)) {
        return '';
    }

    $content = wpautop($content);

    preg_match_all('/<p.*?>/', $content, $p_matches);
    preg_match_all('/<h[1-6].*?>/', $content, $h_matches);

    $p_count = count($p_matches[0]);

    $p_base_class = 'text-xs';
    $p_extra_class = $options['p'] ?? '';
    $p_class = $p_base_class . ' ' . $p_extra_class;

    if ($p_count > 1) {
        $p_class .= ' mb-4';
    }

    $content = str_replace('<p>', '<p class="' . esc_attr(trim($p_class)) . '">', $content);

    $heading_extra_class = $options['heading'] ?? '';

    $content = preg_replace_callback(
        '/<(h[1-6])>(.*?)<\/\1>/i',
        function ($matches) use ($heading_extra_class) {
            $tag = esc_attr($matches[1]);
            $headingContent = $matches[2];

            // Apply period-to-line-break logic to heading content only
            if (!empty(trim($headingContent))) {
                $headingContent = html_entity_decode($headingContent);
                $headingContent = preg_replace('/\s+/u', ' ', $headingContent);
                $headingContent = preg_replace('/\.\s+/', '.<br> ', $headingContent);
                $headingContent = preg_replace('/\.$/', '.<br>', $headingContent);
                $headingContent = trim($headingContent);
            }

            // Escape the heading content while allowing <br> tags
            $headingContent = wp_kses($headingContent, ['br' => []]);

            $classes = match ($matches[1]) {
                'h1' => 'heading-1 mb-6',
                'h2' => 'heading-2 mb-6',
                'h3' => 'heading-3 mb-6',
                'h4' => 'heading-4 mb-6',
                'h5' => 'heading-5 mb-6',
                'h6' => 'heading-6 mb-6',
            };

            // Escape class names
            $safe_classes = esc_attr($classes);
            $safe_heading_extra_class = esc_attr($heading_extra_class);

            return "<{$tag} class=\"{$safe_classes} {$safe_heading_extra_class}\">{$headingContent}</{$tag}>";
        },
        $content
    );

    $content = str_replace('<ul>', '<ul class="list-disc pl-6">', $content);
    $content = str_replace('<li>', '<li class="mb-1">', $content);
    $strong_extra_class = $options['strong'] ?? '';
    $content = str_replace('<strong>', '<strong class="' . esc_attr($strong_extra_class) . '">', $content);
    $content = preg_replace(
        '/<a(.*?)>/i',
        '<a$1 class="hover:underline underline-offset-4">',
        $content
    );

    return $content;
}

/**
 * Enqueue Vite assets dynamically using WordPress native functions
 * Reads manifest.json to get current hashed filenames automatically
 *
 * @return void
 */
function enqueue_vite_assets()
{
    // Enqueue the main CSS file
    $main_css_url = get_vite_asset('resources/css/app.css');
    if (!empty($main_css_url)) {
        wp_enqueue_style(
            'theme-main-css',
            $main_css_url,
            [], // No dependencies
            null  // Use file modification time for version
        );
    }

    // Enqueue JavaScript and its associated CSS chunks
    $assets = get_vite_entry_with_css('resources/js/app.js');

    if ($assets && !empty($assets['js'])) {
        // Enqueue the main JavaScript file
        wp_enqueue_script(
            'theme-app-js',
            $assets['js'],
            [], // No dependencies
            null, // Use file modification time for version
            true  // Load in footer
        );

        // Enqueue any associated CSS files from the JS entry
        if (!empty($assets['css'])) {
            foreach ($assets['css'] as $index => $css_url) {
                wp_enqueue_style(
                    'theme-app-css-' . $index,
                    $css_url,
                    ['theme-main-css'], // Depend on main CSS
                    null  // Use file modification time for version
                );
            }
        }
    }
}

/**
 * Enqueue editor assets (Gutenberg block editor)
 * Bypasses Acorn's Vite facade to avoid manifest parsing issues
 *
 * @return void
 */
function enqueue_editor_assets()
{
    // Load manifest
    $manifest_path = get_template_directory() . '/public/build/manifest.json';
    if (!file_exists($manifest_path)) {
        return;
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);
    if (!$manifest) {
        return;
    }

    // Enqueue dependencies
    if (isset($manifest['editor.deps.json']['file'])) {
        $deps_file = get_template_directory() . '/public/build/' . $manifest['editor.deps.json']['file'];
        if (file_exists($deps_file)) {
            $dependencies = json_decode(file_get_contents($deps_file), true);
            foreach ($dependencies as $dependency) {
                if (!wp_script_is($dependency)) {
                    wp_enqueue_script($dependency);
                }
            }
        }
    }

    // Enqueue editor CSS
    if (isset($manifest['resources/css/editor.css']['file'])) {
        $css_url = get_template_directory_uri() . '/public/build/' . $manifest['resources/css/editor.css']['file'];
        wp_enqueue_style('theme-editor-css', $css_url, [], null);
    }

    // Enqueue editor JS and its associated CSS
    if (isset($manifest['resources/js/editor.js'])) {
        $entry = $manifest['resources/js/editor.js'];
        $base_url = get_template_directory_uri() . '/public/build/';

        // Enqueue JS
        if (isset($entry['file'])) {
            wp_enqueue_script('theme-editor-js', $base_url . $entry['file'], [], null, true);
        }

        // Enqueue associated CSS chunks
        if (isset($entry['css']) && is_array($entry['css'])) {
            foreach ($entry['css'] as $index => $css_file) {
                wp_enqueue_style('theme-editor-js-css-' . $index, $base_url . $css_file, ['theme-editor-css'], null);
            }
        }
    }
}

/**
 * Check if current page/post has any ACF blocks
 *
 * @return bool True if ACF blocks are found, false otherwise
 */
function has_acf_blocks()
{
    global $post;

    $hasAcfBlocks = false;

    if (has_blocks() && isset($post->post_content)) {
        $blocks = parse_blocks($post->post_content);
        foreach ($blocks as $block) {
            if (strpos($block['blockName'] ?? '', 'acf/') !== false) {
                $hasAcfBlocks = true;
                break;
            }
        }
    }

    return $hasAcfBlocks;
}

/**
 * Get related posts of post
 *
 * @param number $post_id is the ID of the current post
 * @param number $related_count is the number of the posts in the query
 * @param array  $args are the optional arguments of the query
 *
 * @return WP_Query
 */
function get_related_posts(int $post_id, $related_count, array $args = []): \WP_Query
{
    $terms = get_the_terms($post_id, 'category');

    if (empty($terms)) {
        $terms = [];
    }

    $term_list = wp_list_pluck($terms, 'slug');

    $post_type = get_post_type($post_id);

    $related_args = [
        'post_type'      => $post_type,
        'posts_per_page' => $related_count ?: -1,
        'post_status'    => 'publish',
        'post__not_in'   => [$post_id],
        'orderby'        => 'rand',
    ];

    if (!empty($term_list)) {
        $related_args['tax_query'] = [
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => $term_list
        ];
    }

    return new \WP_Query($related_args);
}

/**
 * Get related posts IDs
 *
 * @param number $post_id is the ID of the current post
 * @param number $related_count is the number of the posts in the query
 * @param array  $args are the optional arguments of the query
 *
 * @return array [array]
 */
function get_related_posts_ids(int $post_id, $related_count, array $args = []): array
{
    $terms     = get_the_terms($post_id, 'category') ?? [];
    $term_list = $terms ? wp_list_pluck($terms, 'slug') : [];
    $post_type = get_post_type($post_id);
    $tax_query = [];

    if (!empty($term_list)) {
        $tax_query = [
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => $term_list
        ];
    }

    $related_post_ids = get_posts([
        'post_type'      => $post_type,
        'posts_per_page' => $related_count ?: -1,
        'post_status'    => 'publish',
        'post__not_in'   => [$post_id],
        'orderby'        => 'rand',
        'tax_query'      => $tax_query
    ]);

    return array_map(static function ($post) {
        return $post->ID ?? null;
    }, $related_post_ids);
}
