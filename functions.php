<?php

use Roots\Acorn\Application;

define('TEXT_DOMAIN', 'runa');
define('RUNA_FEATURED_POST_META_KEY', 'featured_post');

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
function remove_trailing_slash_from_acf_links($value, $post_id, $field)
{
    if (is_array($value) && isset($value['url'])) {
        $value['url'] = untrailingslashit($value['url']);
    }
    return $value;
}

// Filter WordPress navigation menu links
add_filter('wp_nav_menu_objects', 'remove_trailing_slash_from_nav_menu');
function remove_trailing_slash_from_nav_menu($menu_items)
{
    foreach ($menu_items as $menu_item) {
        $menu_item->url = untrailingslashit($menu_item->url);
    }
    return $menu_items;
}

// Filter social network URLs and other URLs in content
add_filter('the_content', 'remove_trailing_slash_from_content_links');
function remove_trailing_slash_from_content_links($content)
{
    return preg_replace('/href="([^"]+)\/"/', 'href="$1"', $content);
}

/**
 * Add security headers for WP Engine hosting
 * Addresses security findings: CSP, HSTS, X-Frame-Options, X-Powered-By
 */
add_action('send_headers', 'add_security_headers');
function add_security_headers()
{
    // Only add headers on front-end (not in admin)
    if (!is_admin()) {
        // Build dynamic CSP that allows both frontend and backend domains
        $csp_domains = get_csp_allowed_domains();

        // Content Security Policy - Defense against XSS attacks
        // Dynamically allows assets from both frontend domain (runa.io) and backend domain (wpengine)
        $csp = "default-src 'self' {$csp_domains}; ";
        $csp .= "script-src 'self' {$csp_domains} 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com https://consent.cookiebot.com; ";
        $csp .= "style-src 'self' {$csp_domains} 'unsafe-inline' https://fonts.googleapis.com; ";
        $csp .= "font-src 'self' {$csp_domains} https://fonts.gstatic.com; ";
        $csp .= "img-src 'self' {$csp_domains} data: https:; ";
        $csp .= "frame-src 'self'; ";
        $csp .= "connect-src 'self' {$csp_domains} https://www.google-analytics.com https://consent.cookiebot.com;";

        header("Content-Security-Policy: {$csp}");

        // HTTP Strict Transport Security - Force HTTPS
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }

        // X-Frame-Options - Prevent clickjacking
        header('X-Frame-Options: SAMEORIGIN');

        // Remove X-Powered-By header (hide server info)
        header_remove('X-Powered-By');

        // Additional security headers
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
}

/**
 * Get CSP-allowed domains based on environment
 * Allows assets from both frontend and backend domains
 *
 * @return string Space-separated list of allowed domains for CSP
 */
function get_csp_allowed_domains()
{
    // Environment configuration: frontend domain => backend domain
    $environment_map = [
        'runa.io' => 'https://runaio.wpenginepowered.com',
        'staging.runa.io' => 'https://runaiostaging.wpenginepowered.com',
        'runastg.wpenginepowered.com' => 'https://runaiostaging.wpenginepowered.com', // Staging backend
        'runa.local' => '', // Local development has no separate backend
    ];

    // Reverse map: backend domain => frontend domain (for when home_url is backend)
    $backend_to_frontend = [
        'runaio.wpenginepowered.com' => 'https://runa.io',
        'runaiostaging.wpenginepowered.com' => 'https://staging.runa.io',
        'runastg.wpenginepowered.com' => 'https://staging.runa.io',
    ];

    $allowed = [];

    // Get home_url hostname
    $site_url = home_url();
    $parsed_site = parse_url($site_url);
    $site_host = $parsed_site['host'] ?? '';

    // Get current request hostname (might be different from home_url when behind proxy)
    $request_host = $_SERVER['HTTP_HOST'] ?? '';

    // Check if home_url is a frontend domain
    if (isset($environment_map[$site_host]) && !empty($environment_map[$site_host])) {
        $allowed[] = $environment_map[$site_host];
    }

    // Check if home_url is a backend domain
    if (isset($backend_to_frontend[$site_host])) {
        // home_url is backend, allow the corresponding frontend
        $frontend_domain = $backend_to_frontend[$site_host];
        $allowed[] = $frontend_domain;

        // Also allow the backend itself
        $allowed[] = 'https://' . $site_host;
    }

    // Check request hostname separately (in case it differs from home_url)
    if ($request_host !== $site_host) {
        if (isset($environment_map[$request_host]) && !empty($environment_map[$request_host])) {
            $backend = $environment_map[$request_host];
            if (!in_array($backend, $allowed)) {
                $allowed[] = $backend;
            }
        }

        if (isset($backend_to_frontend[$request_host])) {
            $frontend = $backend_to_frontend[$request_host];
            if (!in_array($frontend, $allowed)) {
                $allowed[] = $frontend;
            }
        }
    }

    // Always allow local development domains
    if (defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE === 'local') {
        $allowed[] = 'http://runa.local';
        $allowed[] = 'https://runa.local';
    }

    // Remove duplicates and return
    $allowed = array_unique($allowed);
    return !empty($allowed) ? implode(' ', $allowed) : '';
}

