<?php

namespace App\PathGenerators;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class DonorLogoPathGenerator implements PathGenerator
{
    /**
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        // everything goes into `donor-logos/`
        return 'donor-logos/';
    }

    /**
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return 'donor-logos/conversions/';
    }

    /**
     * Get the path for responsive images, relative to the root storage path.
     * (optional, if you use responsive images)
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return 'donor-logos/responsive/';
    }
}
