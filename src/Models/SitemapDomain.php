<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Models;

use Illuminate\Database\Eloquent\Model;

class SitemapDomain extends Model
{
    protected $fillable = [
        'host',
        'port',
    ];

    protected $casts = [
        'port' => 'integer',
    ];
}
