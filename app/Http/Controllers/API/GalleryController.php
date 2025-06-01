<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PhotoResource;
use App\Models\Assets\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => ['required', 'integer', 'min:2018', 'max:'.now()->year],
            'files' => ['required', 'array'],
            'files.*' => ['file', 'image', 'max:5120'], // max 5 MB each
        ]);

        /** @var UploadedFile[] $files */
        $files = (array) $request->file('files');

        $year = (int) $validated['year'];
        $created = [];

        foreach ($files as $file) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $uniqueName = $originalName.'-'.time().'-'.Str::random(6).".{$extension}";

            $photo = Photo::create([
                'year' => $year,
            ]);

            $photo->addMedia($file)
                ->usingFileName($uniqueName)
                ->toMediaCollection('gallery');

            // for our front-end response
            $photo->load('media');

            $created[] = $photo;
        }

        return PhotoResource::collection($created)
            ->response()
            ->setStatusCode(201);
    }

    public function destroy(Photo $photo)
    {
        $photo->clearMediaCollection('gallery');

        return $this->deleteAndRespond([$photo->id]);
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:photos,id'],
        ]);

        return $this->deleteAndRespond($validated['ids']);
    }

    /**
     * @param  int[]  $ids
     * @return \Illuminate\Http\JsonResponse
     */
    protected function deleteAndRespond(array $ids)
    {
        Photo::destroy($ids);

        return response()->json([
            'deleted' => $ids,
        ], 200);
    }
}
