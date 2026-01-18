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
Route::post('sitemaps/{name}-{counter}.xml', [SitemapController::class, 'show'])->name('sitemap.show');

```
