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
            Route::post('sitemaps/{name}-{index}.xml', [SitemapController::class, 'show'])
                ->whereNumber('index')
                ->name('sitemap.show');
        };
    }
}
