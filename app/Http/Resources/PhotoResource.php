<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Assets\Photo
 */
class PhotoResource extends JsonResource
{
    /**
     * Transform the Photo model into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $media = $this->getFirstMedia('gallery');

        return [
            'id' => $this->id,
            'year' => $this->year,
            'url' => $this->url,
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
