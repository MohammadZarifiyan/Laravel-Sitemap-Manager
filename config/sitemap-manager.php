<?php

return [
    /**
     * The filesystem disk used to store generated sitemap files.
     * Must match a disk defined in config/filesystems.php.
     */
    'disk' => env('SITEMAP_DISK', 'local'),

    /**
     * The directory (relative to the selected disk root) where sitemap files will be saved.
     */
    'directory' => env('SITEMAP_DIRECTORY', 'sitemaps'),

    /**
     * Maximum number of URL tags per sitemap file.
     * When exceeded, multiple sitemap files will be generated automatically.
     */
    'tags-per-sitemap' => env('TAGS_PER_SITEMAP', 5000),

    /**
     * An array of sitemap registries/providers.
     * Used to register custom sources for sitemap entries.
     */
    'registries' => [],
];
