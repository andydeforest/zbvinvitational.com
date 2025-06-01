<?php

namespace App\Http\Controllers;

use App\Http\Resources\DonorLogoResource;
use App\Models\Assets\DonorLogo;
use App\Models\Assets\Photo;
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
        $years = Photo::availableYears();

        /** @var string|null $yearParam */
        $yearParam = $request->get('year');

        $requestedYear = $yearParam !== null
            ? $yearParam
            : '';

        $activeYear = $years->contains($requestedYear)
            ? $requestedYear
            : (string) $years->first();

        $images = Photo::imagesForYear($activeYear);

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
