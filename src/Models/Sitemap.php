<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Models;

use MohammadZarifiyan\LaravelSitemapManager\Events\SitemapDeleted;
use Illuminate\Database\Eloquent\Model;

class Sitemap extends Model
{
    protected $fillable = [
        'name',
        'global',
        'disk',
        'path',
    ];

    protected $casts = [
        'global' => 'boolean',
    ];

    protected $dispatchesEvents = [
        'deleted' => SitemapDeleted::class,
    ];

    public function domains()
    {
        return $this->hasMany(SitemapDomain::class);
    }
}
