<?php

namespace Iquesters\Product;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Command;
use Iquesters\Product\Database\Seeders\ProductSeeder;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/product.php', 'product');

        $this->registerSeedCommand();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load package routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Load package migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load package views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'product');

        if ($this->app->runningInConsole()) {
            $this->commands([
                'command.product.seed'
            ]);
        }

        // Publish configuration and views
        $this->publishes([
            __DIR__ . '/../config/product.php' => config_path('product.php'),
            __DIR__ . '/../resources/views/layouts/package.blade.php' => resource_path('views/vendor/product/layouts/package.blade.php'),
        ], 'product-config');
    }

    protected function registerSeedCommand(): void
    {
        $this->app->singleton('command.product.seed', function ($app) {
            return new class extends Command {
                protected $signature = 'product:seed';
                protected $description = 'Seed Product module data';

                public function handle()
                {
                    $this->info('Running Product Seeder...');
                    $seeder = new ProductSeeder();
                    $seeder->setCommand($this);
                    $seeder->run();
                    $this->info('Product seeding completed!');
                    return 0;
                }
            };
        });
    }
}