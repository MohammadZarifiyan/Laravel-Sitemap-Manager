<?php

namespace MohammadZarifiyan\LaravelSitemapManager\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use MohammadZarifiyan\LaravelSitemapManager\DataTransferObjects\Domain;
use MohammadZarifiyan\LaravelSitemapManager\Enums\SitemapRestrictionType;
use MohammadZarifiyan\LaravelSitemapManager\Interfaces\RegistryInterface;
use MohammadZarifiyan\LaravelSitemapManager\Interfaces\RestrictedRegistryInterface;
use MohammadZarifiyan\LaravelSitemapManager\Models\Sitemap as SitemapModel;
use Spatie\Sitemap\Sitemap;

class RefreshSitemapsCommand extends Command
{
    protected $signature = 'sitemap-manager:refresh-sitemaps';

    protected $description = 'Create new sitemaps and deletes old sitemaps.';

    protected array $savedSitemaps = [];

    public function handle(): int
    {
        $registries = config('sitemap-manager.registries');

        foreach ($registries as $registry) {
            if ($registry instanceof RegistryInterface === false) {
                throw new Exception('Registry must be instance of \MohammadZarifiyan\LaravelSitemapManager\Interfaces\Registry.');
            }

            $tagsPage = 1;

            do {
                $tags = $registry->tags($tagsPage);

                if ($tags->total() === 0) {
                    break;
                }

                $sitemap = $this->generateSitemap($tags);
                $sitemapModel = $this->updateOrCreateSitemapModel(
                    $registry->getName(),
                    $registry instanceof RestrictedRegistryInterface ? $registry->restrictionType() : null,
                    $sitemap
                );

                $sitemapModel->domains()->delete();

                if ($registry instanceof RestrictedRegistryInterface) {
                    $this->attachDomains($sitemapModel, $registry->domains());
                }

                $this->savedSitemaps[$registry->getName()][] = $sitemapModel->getKey();

                $tagsPage++;
            }
            while ($tags->hasMorePages());
        }

        $this->removeOldSitemaps();

        return Command::SUCCESS;
    }

    protected function generateSitemap(LengthAwarePaginator $tags): Sitemap
    {
        $sitemap = Sitemap::create();

        return $sitemap->add($tags->items());
    }

    protected function updateOrCreateSitemapModel(string $name, SitemapRestrictionType $restrictionType, Sitemap $sitemap): SitemapModel
    {
        $disk = config('sitemap-manager.disk');
        $path = $this->generateSitemapPath();

        $this->line(sprintf('Saving %s sitemap in the %s (%s).', $name, $path, $disk));

        $result = Storage::disk($disk)->put($path, $sitemap->render());

        if (!$result) {
            throw new Exception('An error occurred while saving the sitemap.');
        }

        $sitemapModel = SitemapModel::updateOrCreate(
            compact('disk', 'path'),
            [
                'name' => $name,
                'restriction_type' => $restrictionType
            ]
        );

        $this->info(sprintf('The sitemap has been saved. ID: %s.', $sitemapModel->getKey()));

        return $sitemapModel;
    }

    protected function generateSitemapPath(): string
    {
        $directory = config('sitemap-manager.directory');

        return $directory . DIRECTORY_SEPARATOR . Str::uuid() . '.xml';
    }

    protected function attachDomains(SitemapModel $sitemapModel, Collection|LazyCollection $domains): void
    {
        $domains->chunk(100)->each(function (Collection $domainsCollection) use ($sitemapModel, $domains) {
            $recordsCollection = $domainsCollection->map(fn (Domain $domain) => [
                'host' => $domain->host,
                'port' => $domain->port,
            ]);

            $sitemapModel->domains()->createMany($recordsCollection->toArray());
        });
    }

    protected function removeOldSitemaps(): void
    {
        $this->line('Removing old sitemaps...');

        $ids = Arr::flatten($this->savedSitemaps);

        $this->withProgressBar(
            SitemapModel::whereKeyNot($ids)->cursor(),
            function (SitemapModel $sitemapModel) {
                $sitemapModel->delete();
            }
        );

        $this->newLine();
    }
}
