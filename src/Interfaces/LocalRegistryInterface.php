<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Interfaces;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

interface LocalRegistryInterface extends RegistryInterface
{
    public function domains(): Collection|LazyCollection;
}
