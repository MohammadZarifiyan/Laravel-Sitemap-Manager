<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Listeners;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Storage;
use MohammadZarifiyan\LaravelSitemapManager\Events\SitemapDeleted;

class DeleteFile implements ShouldHandleEventsAfterCommit
{
    public function handle(SitemapDeleted $event): void
    {
        Storage::disk($event->sitemap->disk)->delete($event->sitemap->path);
    }
}
