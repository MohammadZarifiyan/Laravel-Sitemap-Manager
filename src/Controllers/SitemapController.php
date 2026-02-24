<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use MohammadZarifiyan\LaravelSitemapManager\Enums\SitemapRestrictionType;
use MohammadZarifiyan\LaravelSitemapManager\Models\Sitemap as SitemapModel;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Sitemap;

class SitemapController
{
    public function index(Request $request)
    {
        $sitemapIndex = SitemapIndex::create();
        $groupedSitemaps = SitemapModel::where(function (Builder $builder) use ($request) {
            $builder->whereNull('restriction_type');
            $builder->orWhere(function (Builder $builder) use ($request) {
                $builder->where('restriction_type', SitemapRestrictionType::Legalization);
                $builder->whereHas('domains', function (Builder $builder) use ($request) {
                    $builder->where('host', $request->getHost());
                    $builder->where(function (Builder $builder) use ($request) {
                        $builder->where('port', $request->getPort());
                        $builder->orWhereNull('port');
                    });
                });
            });
            $builder->orWhere(function (Builder $builder) use ($request) {
                $builder->where('restriction_type', SitemapRestrictionType::Prohibition);
                $builder->whereDoesntHave('domains', function (Builder $builder) use ($request) {
                    $builder->where('host', $request->getHost());
                    $builder->where(function (Builder $builder) use ($request) {
                        $builder->where('port', $request->getPort());
                        $builder->orWhereNull('port');
                    });
                });
            });
        })
            ->get()
            ->groupBy('name');

        foreach ($groupedSitemaps as $name => $sitemaps) {
            foreach ($sitemaps as $index => $sitemap) {
                $url = route('sitemap.show', [
                    'slug' => sprintf('%s-%d', $name, $index),
                ]);
                $sitemapTag = Sitemap::create($url);
                $sitemapTag->setLastModificationDate($sitemap->updated_at);

                $sitemapIndex->add($sitemapTag);
            }
        }

        return $sitemapIndex->toResponse($request);
    }

    public function show(Request $request, string $slug)
    {
        if (!preg_match('/^(.*)-(\d+)$/', $slug, $match)) {
            abort(404);
        }

        $name = $match[1];
        $index = $match[2];

        $sitemap = SitemapModel::where('name', $name)
            ->where(function (Builder $builder) use ($request) {
                $builder->whereNull('restriction_type');
                $builder->orWhere(function (Builder $builder) use ($request) {
                    $builder->where('restriction_type', SitemapRestrictionType::Legalization);
                    $builder->whereHas('domains', function (Builder $builder) use ($request) {
                        $builder->where('host', $request->getHost());
                        $builder->where(function (Builder $builder) use ($request) {
                            $builder->where('port', $request->getPort());
                            $builder->orWhereNull('port');
                        });
                    });
                });
                $builder->orWhere(function (Builder $builder) use ($request) {
                    $builder->where('restriction_type', SitemapRestrictionType::Prohibition);
                    $builder->whereDoesntHave('domains', function (Builder $builder) use ($request) {
                        $builder->where('host', $request->getHost());
                        $builder->where(function (Builder $builder) use ($request) {
                            $builder->where('port', $request->getPort());
                            $builder->orWhereNull('port');
                        });
                    });
                });
            })
            ->offset($index)
            ->firstOrFail();

        $url = route('sitemap.show', [
            'slug' => sprintf('%s-%d', $name, $index)
        ]);

        return response()->streamDownload(
            function () use ($sitemap) {
                $stream = Storage::disk($sitemap->disk)->readStream($sitemap->path);

                while(ob_get_level() > 0) {
                    ob_end_flush();
                }

                fpassthru($stream);
            },
            basename($url)
        );
    }
}
