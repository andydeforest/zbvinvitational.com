<?php

namespace App\PathGenerators;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class DonorLogoPathGenerator implements PathGenerator
{
    protected function basePath(Media $media): string
    {
        return "donor-logos/{$media->getKey()}/";
    }

    /**
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        return $this->basePath($media);
    }

    /**
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->basePath($media).'conversions/';
    }

    /**
     * Get the path for responsive images, relative to the root storage path.
     * (optional, if you use responsive images)
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->basePath($media).'responsive/';
    }
}
