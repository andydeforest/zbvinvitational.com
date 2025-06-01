<?php

namespace App\PathGenerators;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class PhotoPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $year = $this->extractYearFromModel($media);

        if (! $year) {
            return 'gallery/';
        }

        return "gallery/{$year}/";
    }

    public function getPathForConversions(Media $media): string
    {
        $year = $this->extractYearFromModel($media);

        if (! $year) {
            return 'gallery/conversions/';
        }

        return "gallery/{$year}/conversions/";
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        $year = $this->extractYearFromModel($media);

        if (! $year) {
            return 'gallery/responsive/';
        }

        return "gallery/{$year}/responsive/";
    }

    protected function extractYearFromModel(Media $media): ?int
    {
        $model = $media->model;

        if (isset($model->year) && is_int($model->year)) {
            return $model->year;
        }

        return null;
    }
}
