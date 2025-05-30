<!-- resources/views/emails/contact.blade.php -->
@extends('mail.layout')

@section('content')
    <h2>Thank You for Your ZBVInvitational.com Order!</h2>

    <p><strong>Name:</strong> {{ $order->first_name }}&nbsp;{{ $order->last_name }}</p>
    <p><strong>Email:</strong> {{ $order->email }}</p>
    <p><strong>Amount:</strong> ${{ number_format($order->total_cents / 100, 2) }}</p>
    <p><strong>Order ID#:</strong> <a href="{{ url('shop/confirmation/' . $order->public_id) }}"
            target="_blank">{{ $order->public_id }}</a></p>
    <p><strong>Ordered Items:</strong></p>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ $item->product->price_dollars }}</td>
                    <td>${{ number_format($item->unit_price_dollars, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3"></td>
                <td><strong>${{ number_format($order->total_cents / 100, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>
    <hr>
    @php
        $rawDate = \Settings::get(\App\Enums\SettingKey::EventDate);
        $eventDate = $rawDate ? \Carbon\Carbon::parse($rawDate) : null;
    @endphp
    @if ($eventDate)
        <p>We are looking forward to seeing you on the <strong>{{ $eventDate->format('F j, Y') }}</strong>! The golf
            tournament will begin at 10:00am. Please check the website for more details.</p>
    @endif
    <p>Please note our Tax ID #<strong>83-2803947</strong> for your records. All donations are tax-deductible.</p>
    <p>If you have any questions or concerns with your order, please <a href="{{ url('contact') }}" target="_blank">contact
            us</a>.</p>
@endsection
