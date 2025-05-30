<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function show()
    {
        return Inertia::render('Admin/Contact/Index');
    }
}
