<?php

namespace App\Http\Controllers;

use App\Support\DonorPageData;
use App\Support\GalleryPageData;
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
        $years = GalleryPageData::years();

        /** @var string|null $yearParam */
        $yearParam = $request->get('year');

        $requestedYear = $yearParam !== null
            ? $yearParam
            : '';

        $activeYear = $years->contains($requestedYear)
            ? $requestedYear
            : (string) $years->first();

        $galleryPage = GalleryPageData::imagesForYear($activeYear);

        return Inertia::render('Public/Gallery', [
            'years' => $years,
            'activeYear' => $activeYear,
            'images' => $galleryPage['data'],
            'galleryPagination' => [
                'current_page' => $galleryPage['current_page'],
                'next_page' => $galleryPage['next_page'],
                'has_more' => $galleryPage['has_more'],
                'total' => $galleryPage['total'],
            ],
        ]);
    }

    public function donors()
    {
        return Inertia::render('Public/Donors', [
            'individuals' => DonorPageData::individuals(),
            'logos' => [
                'data' => collect(DonorPageData::logos())->shuffle()->values(),
            ],
        ]);
    }

    public function faq()
    {
        return Inertia::render('Public/FAQ');
    }
}
