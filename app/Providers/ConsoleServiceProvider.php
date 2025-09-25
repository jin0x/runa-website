<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\Commands\ImportCompanies;

class ConsoleServiceProvider extends ServiceProvider
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
        // Register WP CLI commands only if WP CLI is available
        if (defined('WP_CLI') && WP_CLI) {
            $this->registerWpCliCommands();
        }
    }

    /**
     * Register WP CLI commands
     *
     * @return void
     */
    protected function registerWpCliCommands()
    {
        // Register the import-companies command under 'runa' namespace
        \WP_CLI::add_command('runa import-companies', ImportCompanies::class);
    }
}