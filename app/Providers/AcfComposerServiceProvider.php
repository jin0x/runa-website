<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AcfComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('acf-composer', function ($app) {
            return new \Log1x\AcfComposer\AcfComposer($app);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (class_exists('Log1x\AcfComposer\AcfComposer')) {
            $this->app->make('acf-composer')->compose();
        }
    }
}
