<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PhotoResource;
use App\Models\Assets\Photo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class GalleryController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $maxUploadKilobytes = (int) ceil(((int) config('media-library.max_file_size', 10 * 1024 * 1024)) / 1024);

        $validated = $request->validate([
            'year' => ['required', 'integer', 'min:2018', 'max:'.now()->year],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['required', 'file', 'image', "max:{$maxUploadKilobytes}"],
        ]);

        /** @var UploadedFile[] $files */
        $files = (array) $request->file('files');

        $year = (int) $validated['year'];
        $created = [];

        try {
            foreach ($files as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $uniqueName = $originalName.'-'.time().'-'.Str::random(6).".{$extension}";

                $photo = Photo::create([
                    'year' => $year,
                ]);

                $created[] = $photo;

                $photo->addMedia($file)
                    ->usingFileName($uniqueName)
                    ->toMediaCollection('gallery');

                $photo->load('media');
            }
        } catch (Throwable $exception) {
            foreach ($created as $photo) {
                $photo->delete();
            }

            Log::error('Gallery upload failed.', [
                'message' => $exception->getMessage(),
                'exception' => $exception,
                'disk' => config('media-library.disk_name'),
                'user_id' => $request->user()?->id,
                'year' => $year,
                'file_names' => array_map(
                    static fn (UploadedFile $file): string => $file->getClientOriginalName(),
                    $files
                ),
            ]);

            return response()->json([
                'message' => 'Gallery upload failed: '.$exception->getMessage(),
            ], 500);
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
