<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Interfaces;

use Generator;

interface RegistryInterface
{
    public function getName(): string;

    public function tags(): Generator;
}
