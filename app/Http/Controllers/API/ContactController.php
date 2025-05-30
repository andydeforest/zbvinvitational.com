<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Traits\HandlesLatest;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use HandlesLatest;

    public function index(Request $request)
    {
        $contacts = $this->applyLatest($request, Contact::query())->get();

        return $contacts;
    }
}
