<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\StoreContactRequest;
use App\Mail\ContactMessage;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Contact');
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'type' => $data['type'],
            'message' => $data['message'],
            'order_number' => $data['orderNumber'] ?? null,
        ];

        $contact = Contact::create($payload);

        Mail::to(config('mail.to.address'))->send(new ContactMessage($contact));

        return redirect()
            ->route('contact')
            ->with('success', 'Message sent.');
    }
}
