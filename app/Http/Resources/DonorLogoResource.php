<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Assets\DonorLogo
 */
class DonorLogoResource extends JsonResource
{
    public function toArray($request): array
    {
        // DonorLogos will only have one media type, so we'll flatten the media array
        $media = $this->media->first();

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
                'original_url' => $media->getFullUrl(),
            ] : null,
        ];
    }
}
