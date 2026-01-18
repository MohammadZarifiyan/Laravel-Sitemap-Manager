<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use MohammadZarifiyan\LaravelSitemapManager\Models\Sitemap as SitemapModel;
use Illuminate\Http\Request;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Sitemap;

class SitemapController
{
    public function index(Request $request)
    {
        $sitemapIndex = SitemapIndex::create();
        $groupedSitemaps = SitemapModel::where('global', true)
            ->orWhereRelation('domains', function (Builder $builder) use ($request) {
                $builder->where('host', $request->getHost());
                $builder->where(function (Builder $builder) use ($request) {
                    $builder->where('port', $request->getPort());
                    $builder->orWhereNull('port');
                });
            })
            ->get()
            ->groupBy('name');

        foreach ($groupedSitemaps as $name => $sitemaps) {
            foreach ($sitemaps as $index => $sitemap) {
                $sitemapTag = Sitemap::create(
                    route('sitemap.show', ['name' => $name, 'counter' => $index + 1]),
                );
                $sitemapTag->setLastModificationDate($sitemap->updated_at);

                $sitemapIndex->add($sitemapTag);
            }
        }

        return $sitemapIndex->toResponse($request);
    }

    public function show(Request $request, string $name, int $counter)
    {
        $sitemap = SitemapModel::where('name', $name)
            ->where(function (Builder $builder) use ($request) {
                $builder->where('global', true);
                $builder->orWhereRelation('domains', function (Builder $builder) use ($request) {
                    $builder->where('host', $request->getHost());
                    $builder->where(function (Builder $builder) use ($request) {
                        $builder->where('port', $request->getPort());
                        $builder->orWhereNull('port');
                    });
                });
            })
            ->offset($counter - 1)
            ->firstOrFail();

        return response()->streamDownload(
            function () use ($sitemap) {
                $stream = Storage::disk($sitemap->disk)->readStream($sitemap->path);

                while(ob_get_level() > 0) {
                    ob_end_flush();
                }

                fpassthru($stream);
            },
            basename($sitemap->path)
        );
    }
}
