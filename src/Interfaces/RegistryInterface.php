<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RegistryInterface
{
    public function getName(): string;

    public function tags(int $page): LengthAwarePaginator;
}
