<?php

namespace App\Support;

use App\Http\Resources\DonorLogoResource;
use App\Models\Assets\DonorLogo;
use App\Models\Donor;
use Illuminate\Support\Facades\Cache;

class DonorPageData
{
    private const INDIVIDUALS_CACHE_KEY = 'public-donors-page:individuals';

    private const LOGOS_CACHE_KEY = 'public-donors-page:logos';

    public static function individuals(): array
    {
        return Cache::remember(
            self::INDIVIDUALS_CACHE_KEY,
            now()->addDay(),
            fn () => Donor::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn (Donor $donor): array => [
                    'id' => $donor->id,
                    'name' => $donor->name,
                ])
                ->all()
        );
    }

    public static function logos(): array
    {
        return Cache::remember(
            self::LOGOS_CACHE_KEY,
            now()->addDay(),
            function (): array {
                $logos = DonorLogo::withAttachedMedia()
                    ->with('media')
                    ->get();

                return DonorLogoResource::collection($logos)->resolve();
            }
        );
    }

    public static function flush(): void
    {
        Cache::forget(self::INDIVIDUALS_CACHE_KEY);
        Cache::forget(self::LOGOS_CACHE_KEY);
    }
}
