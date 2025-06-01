<?php

namespace App\PathGenerators;

use Illuminate\Contracts\Container\Container;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class ModelBasedPathGenerator implements PathGenerator
{
    public function __construct(protected Container $container) {}

    protected array $map = [
        \App\Models\Assets\DonorLogo::class => DonorLogoPathGenerator::class,
        \App\Models\Assets\Photo::class => PhotoPathGenerator::class,
    ];

    protected function getGeneratorFor(Media $media): PathGenerator
    {
        $modelClass = $media->model_type;

        if (isset($this->map[$modelClass])) {
            return $this->container->make($this->map[$modelClass]);
        }

        // fallback to spaties default behavior
        return new DefaultPathGenerator;
    }

    public function getPath(Media $media): string
    {
        return $this->getGeneratorFor($media)->getPath($media);
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getGeneratorFor($media)->getPathForConversions($media);
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getGeneratorFor($media)->getPathForResponsiveImages($media);
    }
}
