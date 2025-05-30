<?php

namespace App\Http\Controllers\Admin\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait UploadCoverImage
{
    /**
     * Merge cover_image logic into your $data array:
     *
     * - If a File was uploaded: delete old, store new, set the path
     * - If field exists in request but is null: delete old, set null
     * - Otherwise: remove it so the column is untouched
     *
     * @param  Request  $request  the incoming FormRequest
     * @param  array  $data  validated data
     * @param  string|null  $existingPath  current DB path, or null
     * @param  string  $folder  e.g. "products" or "product-categories"
     * @return array the $data array with cover_image handled
     */
    protected function mergeCoverImage(
        Request $request,
        array $data,
        ?string $existingPath,
        string $folder
    ): array {
        // 1) New upload?
        if ($request->hasFile('cover_image')) {
            if ($existingPath) {
                Storage::disk('public')->delete($existingPath);
            }
            $data['cover_image'] = $request
                ->file('cover_image')
                ->store("{$folder}/covers", 'public');
        }
        // 2) Explicit clear?
        elseif ($request->exists('cover_image') && $request->input('cover_image') === null) {
            if ($existingPath) {
                Storage::disk('public')->delete($existingPath);
            }
            $data['cover_image'] = null;
        }
        // 3) Unchanged — drop the key
        else {
            unset($data['cover_image']);
        }

        return $data;
    }
}
