<?php

namespace MohammadZarifiyan\LaravelSitemapManager;

use Illuminate\Support\Facades\Route;
use MohammadZarifiyan\LaravelSitemapManager\Controllers\SitemapController;

class SitemapManager
{
    public static function routes(): void
    {
        Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
        Route::post('sitemaps/{name}-{index}.xml', [SitemapController::class, 'show'])
            ->whereNumber('index')
            ->name('sitemap.show');
    }
}
