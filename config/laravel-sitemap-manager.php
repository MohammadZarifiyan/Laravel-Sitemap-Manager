<?php

return [
    'disk' => env('SITEMAP_DISK', 'local'),
    'directory' => env('SITEMAP_DIRECTORY', 'sitemaps'),
    'tags-per-sitemap' => env('TAGS_PER_SITEMAP', 5000),
    'registries' => [],
];
