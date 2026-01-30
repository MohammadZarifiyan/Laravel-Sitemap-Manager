<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use MohammadZarifiyan\LaravelSitemapManager\Events;
use MohammadZarifiyan\LaravelSitemapManager\Listeners;

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

        Event::listen(
            Events\SitemapDeleted::class,
            Listeners\SitemapDeleted\DeleteFile::class
        );
    }
}
