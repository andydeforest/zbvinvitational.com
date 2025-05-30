<?php

namespace App\Models\Assets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DonorLogo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name'];

    protected static function booted(): void
    {
        static::deleting(function (DonorLogo $logo) {
            $logo->clearMediaCollection();
        });
    }

    public function registerMediaConversions(?Media $media = null): void {}
}
