<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin \App\Models\Assets\DonorLogo
 */
class DonorLogoResource extends JsonResource
{
    public function toArray($request): array
    {
        // DonorLogos will only have one media type, so we'll flatten the media array
        $media = $this->getFirstMedia('donors');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'media' => $media ? [
                'id' => $media->id,
                'uuid' => $media->uuid,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'original_url' => $this->resolveOriginalUrl($media),
            ] : null,
        ];
    }

    protected function resolveOriginalUrl(Media $media): string
    {
        $disk = Storage::disk($media->disk);
        $legacyPath = "donor-logos/{$media->file_name}";

        if ($disk->exists($legacyPath)) {
            return $disk->url($legacyPath);
        }

        return $media->getFullUrl();
    }
}
