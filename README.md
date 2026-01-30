# Introduction

**Sitemap Manager** is a Laravel package for automated sitemap generation and management.
It creates new sitemap files, replaces outdated ones, and keeps everything up to date using a single Artisan command and Laravel’s scheduler.

Ideal for Laravel applications that need **reliable, repeatable, and automated sitemap handling**.

---

# Installation

Install the package via Composer:

```shell
composer require mohammad-zarifiyan/laravel-sitemap-manager
```

## Database Migrations
This package stores sitemap metadata in the database.

Publish and run the migrations:

```shell
php artisan vendor:publish --provider="MohammadZarifiyan\LaravelSitemapManager\Providers\SitemapManagerProvider" --tag="sitemap-manager-migrations"
  ```
```shell
php artisan migrate
```

## Configuration (Optional)
To publish the configuration file:
```shell
php artisan vendor:publish --provider="MohammadZarifiyan\LaravelSitemapManager\Providers\SitemapManagerProvider" --tag="sitemap-manager-config"
```
## Routes
Register the package routes in your `routes/web.php` file:
```php
use MohammadZarifiyan\LaravelSitemapManager\SitemapManager;

SitemapManager::routes();
```
These routes are used to serve sitemap files.

## Scheduling
To keep sitemaps updated automatically, register the command in Laravel’s scheduler:
```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('sitemap-manager:refresh-sitemaps')->daily();
```
Make sure your server cron is configured to run Laravel’s scheduler.

# How It Works

1. Sitemap data is collected from registered sources
2. Sitemap files are generated in chunks
3. Old sitemap files are replaced safely
4. Metadata is persisted in the database
