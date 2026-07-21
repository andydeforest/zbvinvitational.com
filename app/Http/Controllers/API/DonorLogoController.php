<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DonorLogoResource;
use App\Models\Assets\DonorLogo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class DonorLogoController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|image|max:5120',
        ]);

        /** @var UploadedFile[] $files */
        $files = (array) $request->file('files');

        $created = [];

        try {
            foreach ($files as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $uniqueName = $originalName.'-'.time().'-'.Str::random(6).".{$extension}";

                $logo = DonorLogo::create([
                    'name' => $uniqueName,
                ]);

                $created[] = $logo;

                $logo->addMedia($file)
                    ->usingFileName($uniqueName)
                    ->toMediaCollection('donors');
            }
        } catch (Throwable $exception) {
            foreach ($created as $logo) {
                $logo->delete();
            }

            Log::error('Donor logo upload failed.', [
                'message' => $exception->getMessage(),
                'exception' => $exception,
                'disk' => config('media-library.disk_name'),
                'user_id' => $request->user()?->id,
                'file_names' => array_map(
                    static fn (UploadedFile $file): string => $file->getClientOriginalName(),
                    $files
                ),
            ]);

            return response()->json([
                'message' => 'Donor logo upload failed: '.$exception->getMessage(),
            ], 500);
        }

        return DonorLogoResource::collection($created)
            ->response()
            ->setStatusCode(201);
    }

    public function destroy(DonorLogo $donorLogo)
    {
        // single delete
        return $this->deleteAndRespond([$donorLogo->id]);
    }

    public function bulkDestroy(Request $request)
    {
        // validate & pull out an array of IDs
        $ids = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:donor_logos,id',
        ])['ids'];

        return $this->deleteAndRespond($ids);
    }

    /**
     * @param  int[]  $ids
     */
    protected function deleteAndRespond(array $ids)
    {
        DonorLogo::destroy($ids);

        return response()->json([
            'deleted' => $ids,
        ], 200);
    }
}
