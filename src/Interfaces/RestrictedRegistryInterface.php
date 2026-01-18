<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Interfaces;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use MohammadZarifiyan\LaravelSitemapManager\Enums\SitemapRestrictionType;

interface RestrictedRegistryInterface extends RegistryInterface
{
    public function domains(): Collection|LazyCollection;

    public function restrictionType(): SitemapRestrictionType;
}
