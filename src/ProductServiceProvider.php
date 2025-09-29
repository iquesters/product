<?php

namespace Iquesters\Product;

use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/product.php', 'product');
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

        // Publish configuration and views
        $this->publishes([
            __DIR__ . '/../config/product.php' => config_path('product.php'),
            __DIR__ . '/../resources/views/layouts/package.blade.php' => resource_path('views/vendor/product/layouts/package.blade.php'),
        ], 'product-config');
    }
}