<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Mixin;

use Closure;
use Illuminate\Support\Facades\Route;
use MohammadZarifiyan\LaravelSitemapManager\Controllers\SitemapController;

class RouteMixin
{
    public function sitemap(): Closure
    {
        return function () {
            Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
            Route::get('sitemaps/{slug}.xml', [SitemapController::class, 'show'])->name('sitemap.show');
        };
    }
}
