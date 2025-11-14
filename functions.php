<?php

use Roots\Acorn\Application;

define("TEXT_DOMAIN", "runa");

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

Application::configure()
    ->withProviders([
        App\Providers\ThemeServiceProvider::class,
        App\Providers\AcfComposerServiceProvider::class,
        App\Providers\BlockCategoryServiceProvider::class,
        App\Providers\ConsoleServiceProvider::class,
        Log1x\Poet\PoetServiceProvider::class,
    ])
    ->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/


collect(['setup', 'filters', 'helpers'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });




/**
 * Add your Google Fonts here.
 * This is specifically for the theme Sage from roots.io and goes in config.php
 * Change the font name, weights and styles to what you are using as needed.
 */
define('GOOGLE_FONTS', 'Inter:300,400,500:latin');

/**
 * Remove trailing slashes from all link outputs
 */

// Filter ACF Link field outputs
add_filter('acf/load_value/type=link', 'remove_trailing_slash_from_acf_links', 10, 3);
function remove_trailing_slash_from_acf_links($value, $post_id, $field) {
    if (is_array($value) && isset($value['url'])) {
        $value['url'] = untrailingslashit($value['url']);
    }
    return $value;
}

// Filter WordPress navigation menu links
add_filter('wp_nav_menu_objects', 'remove_trailing_slash_from_nav_menu');
function remove_trailing_slash_from_nav_menu($menu_items) {
    foreach ($menu_items as $menu_item) {
        $menu_item->url = untrailingslashit($menu_item->url);
    }
    return $menu_items;
}

// Filter social network URLs and other URLs in content
add_filter('the_content', 'remove_trailing_slash_from_content_links');
function remove_trailing_slash_from_content_links($content) {
    return preg_replace('/href="([^"]+)\/"/', 'href="$1"', $content);
}