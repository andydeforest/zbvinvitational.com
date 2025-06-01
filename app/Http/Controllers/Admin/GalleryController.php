<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assets\Photo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $photos = Photo::with('media')->get();

        return Inertia::render('Admin/Gallery/Index', [
            'photos' => $photos,
        ]);
    }
}
