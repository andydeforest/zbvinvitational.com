<?php

namespace App\Http\Controllers;

use App\Http\Resources\DonorLogoResource;
use App\Models\Assets\DonorLogo;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PagesController extends Controller
{
    public function home()
    {
        $path = 'images/home/intro';

        $files = File::files(public_path($path));

        $imagePaths = collect($files)
            ->unique(fn ($file) => $file->getFilename())
            ->filter(function ($file) {
                $mime = File::mimeType($file->getPathname());

                return $mime && Str::startsWith($mime, 'image');
            })
            ->shuffle()
            ->take(2)
            ->map(fn ($file) => asset($path.'/'.$file->getFilename()))
            ->values();

        return Inertia::render('Public/Home', [
            'randomImages' => $imagePaths,
        ]);
    }

    public function about()
    {
        return Inertia::render('Public/About');
    }

    public function gallery(Request $request)
    {
        // todo: abstract this logic
        $galleryPath = storage_path('app/public/images/gallery');

        if (! File::isDirectory($galleryPath)) {
            File::makeDirectory($galleryPath, 0755, true);
        }

        $years = collect(File::directories($galleryPath))
            ->map(fn ($path) => basename($path))
            ->sortDesc()
            ->values();

        $requestedYear = $request->get('year');

        $activeYear = is_string($requestedYear) && in_array($requestedYear, $years->all(), true)
            ? $requestedYear
            : (string) $years->first();

        $images = collect(File::files("$galleryPath/$activeYear"))
            ->map(fn ($file) => asset("storage/images/gallery/$activeYear/".$file->getFilename()));

        return Inertia::render('Public/Gallery', [
            'years' => $years,
            'activeYear' => $activeYear,
            'images' => $images,
        ]);
    }

    public function donors()
    {
        $individuals = Donor::orderBy('name', 'ASC')->get();
        $logos = DonorLogo::with('media')
            ->get();

        return Inertia::render('Public/Donors', [
            'individuals' => $individuals,
            'logos' => DonorLogoResource::collection($logos->shuffle()),
        ]);
    }

    public function faq()
    {
        return Inertia::render('Public/FAQ');
    }
}
