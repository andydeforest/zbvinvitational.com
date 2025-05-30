<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DonorLogoResource;
use App\Models\Assets\DonorLogo;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class DonorLogoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|image|max:5120',
        ]);

        /** @var UploadedFile[] $files */
        $files = (array) $request->file('files');

        $created = [];

        foreach ($files as $file) {

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $uniqueName = $originalName.'-'.time().'-'.Str::random(6).".{$extension}";

            $logo = DonorLogo::create([
                'name' => $uniqueName,
            ]);

            $logo->addMedia($file)
                ->usingFileName($uniqueName)
                ->toMediaCollection();

            $created[] = $logo;
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
