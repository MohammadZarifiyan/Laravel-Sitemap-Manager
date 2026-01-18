<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Models;

use Illuminate\Database\Eloquent\Model;
use MohammadZarifiyan\LaravelSitemapManager\Enums\SitemapRestrictionType;
use MohammadZarifiyan\LaravelSitemapManager\Events\SitemapDeleted;

class Sitemap extends Model
{
    protected $fillable = [
        'name',
        'restriction_type',
        'disk',
        'path',
    ];

    protected $casts = [
        'restriction_type' => SitemapRestrictionType::class,
    ];

    protected $dispatchesEvents = [
        'deleted' => SitemapDeleted::class,
    ];

    public function domains()
    {
        return $this->hasMany(SitemapDomain::class);
    }
}
