<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BlockCategoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        add_filter('block_categories_all', [$this, 'registerBlockCategories'], 10, 2);
    }

    /**
     * Register custom block categories.
     *
     * @param array $categories Default block categories.
     * @param WP_Post $post The current post.
     * @return array Modified block categories.
     */
    public function registerBlockCategories($categories, $post)
    {
        // Add your custom categories
        $custom_categories = [
            [
                'slug' => 'runa',
                'title' => 'Runa Components',
                'icon' => 'layout', // Optional: WordPress dashicon name
            ],
        ];

        // Insert at the beginning of the categories array
        return array_merge($custom_categories, $categories);
    }
}
