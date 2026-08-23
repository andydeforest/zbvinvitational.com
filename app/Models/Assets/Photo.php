<?php

namespace App\Models\Assets;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Photo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['year'];

    public function scopeWithAttachedMedia(Builder $query): Builder
    {
        return $query->whereHas('media');
    }

    protected static function booted(): void
    {
        static::deleting(function (Photo $photo) {
            $photo->clearMediaCollection();
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->useDisk((string) config('media-library.disk_name', config('filesystems.default', 'public')));
    }

    public function getUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('gallery');

        return $media
            ? $media->getUrl()
            : null;
    }

    public function getThumbUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('gallery');

        return $media
            ? $media->getUrl('thumb')
            : null;
    }

    /**
     * Return a collection of all distinct years
     *
     * @return \Illuminate\Support\Collection<int, lowercase-string&numeric-string&uppercase-string>
     */
    public static function availableYears(): Collection
    {
        /** @var Collection<int,int> $yearsRaw */
        $yearsRaw = static::query()
            ->withAttachedMedia()
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return $yearsRaw->map(
            fn (int $year): string => (string) $year
        );
    }

    /**
     * Given a year string, return a collection of ['full'=> '...', 'thumb'=>… '...'] arrays
     *
     * @return Collection<int,array{full:string,thumb:string}>
     */
    public static function imagesForYear(string $year): Collection
    {
        return static::query()
            ->withAttachedMedia()
            ->where('year', intval($year))
            ->get()
            ->map(fn (Photo $photo): array => [
                'full' => $photo->url,
                'thumb' => $photo->thumb_url,
            ]);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // https://github.com/larastan/larastan/issues/2034
        // @phpstan-ignore method.notFound
        $this
            ->addMediaConversion('thumb')
            ->fit(Fit::Crop, 300, 300)
            ->nonQueued();
    }
}
