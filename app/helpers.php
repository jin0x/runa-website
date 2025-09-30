<?php

/**
 * Theme helpers.
 */

namespace App\Helpers;

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
