<?php

namespace MohammadZarifiyan\LaravelSitemapManager\DataTransferObjects;

class Domain
{
    public function __construct(
        public readonly string $host,
        public readonly ?int $port = null
    ) {
        //
    }
}
