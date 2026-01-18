<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Providers;

use Illuminate\Support\ServiceProvider;

class SitemapManagerProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'../../config/sitemap-manager.php' => config_path('sitemap-manager.php')
        ], 'sitemap-manager-config');

        $this->publishes([
            __DIR__.'../../migrations' => database_path('sitemap-manager.php')
        ], 'sitemap-manager-migrations');
    }
}
