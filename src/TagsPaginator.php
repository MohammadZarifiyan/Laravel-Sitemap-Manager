<?php

namespace MohammadZarifiyan\LaravelSitemapManager;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;

class TagsPaginator
{
    public static function fromArray(int $page, array $items): LengthAwarePaginatorContract
    {
        $tagsCount = count($items);
        $tagsPerPage = config('sitemap-manager.tags-per-sitemap');
        $paginatorTags = array_slice($items, ($page - 1) * $tagsPerPage, $tagsPerPage);

        return new LengthAwarePaginator($paginatorTags, $tagsCount, $tagsPerPage);
    }
}
