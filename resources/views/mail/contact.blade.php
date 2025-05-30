<!-- resources/views/emails/contact.blade.php -->
@extends('mail.layout')

@section('content')
  <h2>New Contact Form Submission</h2>

  <p><strong>Name:</strong> {{ $contact->name }}</p>
  <p><strong>Email:</strong> <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
  <p><strong>Inquiry Type:</strong> {{ ucfirst($contact->type) }}</p>

  @if(! empty($contact->order_number))
    <p><strong>Order #:</strong> {{ $contact->order_number }}</p>
  @endif

  <hr>

  <p>{{ $contact->message }}</p>
@endsection
