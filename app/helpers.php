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

    $content = str_replace('<p>', '<p class="' . trim($p_class) . '">', $content);

    $heading_extra_class = $options['heading'] ?? ''; 

    $content = preg_replace_callback(
        '/<(h[1-6])>(.*?)<\/\1>/i',
        function ($matches) use ($heading_extra_class) {
            $tag = $matches[1];
            $content = $matches[2];

            $classes = match ($tag) {
                'h1' => 'heading-1 mb-6',
                'h2' => 'heading-2 mb-6',
                'h3' => 'heading-3 mb-6',
                'h4' => 'heading-4 mb-6',
                'h5' => 'heading-5 mb-6',
                'h6' => 'heading-6 mb-6',
            };

            return "<{$tag} class=\"{$classes} {$heading_extra_class}\">{$content}</{$tag}>";
        },
        $content
    );

    $content = str_replace('<ul>', '<ul class="list-disc pl-6">', $content);
    $content = str_replace('<li>', '<li class="mb-1">', $content);
    $strong_extra_class = $options['strong'] ?? '';
    $content = str_replace('<strong>', '<strong class="' . $strong_extra_class . '">', $content);
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
                    [], // No dependencies
                    null  // Use file modification time for version
                );
            }
        }
    }
}
