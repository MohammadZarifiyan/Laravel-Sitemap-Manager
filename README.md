# Installation
To install the package in your project, run the following command in your project root folder:
```shell
composer require mohammad-zarifiyan/laravel-sitemap-manager:dev-master
```

Copy these codes into your `web` routes:
```php
use Illuminate\Support\Facades\Route;
use MohammadZarifiyan\LaravelSitemapManager\Controllers\SitemapController;

Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::post('sitemaps/{name}-{index}.xml', [SitemapController::class, 'show'])
    ->whereNumber('index')
    ->name('sitemap.show');
```

Run the following command to publish migrations:
```shell
php artisan vendor:publish --provider="MohammadZarifiyan\LaravelSitemapManager\Providers\InstantServiceProvider" --tag="sitemap-manager-migrations"
```

If you would like to publish the configuration file, run the following command (optional):
```shell
php artisan vendor:publish --provider="MohammadZarifiyan\LaravelSitemapManager\Providers\InstantServiceProvider" --tag="sitemap-manager-config"
```
