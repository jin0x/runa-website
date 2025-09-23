<?php

use Roots\Acorn\Application;
use Illuminate\Support\Facades\Vite;

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
 * Add custom @font-face styles for the ABC Diatype Rounded font.
 *
 * This function adds inline styles to define the ABC Diatype Rounded font family using @font-face,
 * and sets it as the default font for the body element. The font files are located in
 * the theme's assets/fonts directory.
 *
 * @since Runa 1.0
 */
add_action('wp_head', function () {
    // Helper to handle Vite::asset() returning array in beta versions
    $get_asset_url = function ($path) {
        $asset = Vite::asset($path);
        return is_array($asset) ? $asset['url'] : $asset;
    };

    echo "<style>
        @font-face {
            font-family: 'Lineca';
            src: url('" . $get_asset_url('resources/fonts/Lineca-Regular.woff2') . "') format('woff2'),
                url('" . $get_asset_url('resources/fonts/Lineca-Regular.woff') . "') format('woff');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'Lineca';
            src: url('" . $get_asset_url('resources/fonts/Lineca-Bold.woff2') . "') format('woff2'),
                url('" . $get_asset_url('resources/fonts/Lineca-Bold.woff') . "') format('woff');
            font-weight: 700;
            font-style: normal;
        }
        body {
            font-family: 'Lineca', sans-serif;
        }
    </style>";
}, 100);

/**
 * Add your Google Fonts here.
 * This is specifically for the theme Sage from roots.io and goes in config.php
 * Change the font name, weights and styles to what you are using as needed.
 */
define('GOOGLE_FONTS', 'Inter:300,400,500:latin');
