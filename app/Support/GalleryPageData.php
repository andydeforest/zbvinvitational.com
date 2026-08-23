<?php

namespace App\Support;

use App\Models\Assets\Photo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GalleryPageData
{
    public const PER_PAGE = 24;

    private const YEARS_CACHE_KEY = 'public-gallery-page:years';

    private const IMAGES_CACHE_PREFIX = 'public-gallery-page:images:';

    public static function years(): Collection
    {
        return Cache::remember(
            self::YEARS_CACHE_KEY,
            now()->addDay(),
            fn () => Photo::availableYears()
        );
    }

    /**
     * @return array{
     *     data: array<int, array{id:int, full:string, thumb:string}>,
     *     current_page: int,
     *     next_page: int|null,
     *     has_more: bool,
     *     total: int,
     * }
     */
    public static function imagesForYear(string $year, int $page = 1): array
    {
        $page = max(1, $page);
        $images = self::allImagesForYear($year);
        $offset = ($page - 1) * self::PER_PAGE;
        $pageImages = array_slice($images, $offset, self::PER_PAGE);
        $hasMore = $offset + self::PER_PAGE < count($images);

        return [
            'data' => $pageImages,
            'current_page' => $page,
            'next_page' => $hasMore ? $page + 1 : null,
            'has_more' => $hasMore,
            'total' => count($images),
        ];
    }

    public static function flush(): void
    {
        $years = self::years();

        Cache::forget(self::YEARS_CACHE_KEY);

        $years->each(
            fn (string $year) => Cache::forget(self::IMAGES_CACHE_PREFIX.$year)
        );
    }

    /**
     * @return array<int, array{id:int, full:string, thumb:string}>
     */
    private static function allImagesForYear(string $year): array
    {
        return Cache::remember(
            self::IMAGES_CACHE_PREFIX.$year,
            now()->addDay(),
            fn () => Photo::query()
                ->withAttachedMedia()
                ->with('media')
                ->where('year', (int) $year)
                ->orderBy('id')
                ->get()
                ->map(function (Photo $photo): ?array {
                    $full = $photo->url;
                    $thumb = $photo->thumb_url;

                    if (! $full || ! $thumb) {
                        return null;
                    }

                    return [
                        'id' => $photo->id,
                        'full' => $full,
                        'thumb' => $thumb,
                    ];
                })
                ->filter()
                ->values()
                ->all()
        );
    }
}
