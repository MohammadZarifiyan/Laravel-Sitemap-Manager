<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Events;

use MohammadZarifiyan\LaravelSitemapManager\Models\Sitemap;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SitemapDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(public Sitemap $sitemap)
    {
        //
    }
}
