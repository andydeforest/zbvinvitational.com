<?php

namespace Tests\Unit;

use App\Events\OrderPaid;
use App\Listeners\SendOrderReceipt;
use App\Mail\OrderReceipt;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SendOrderReceiptTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_queues_an_order_receipt_email_when_order_paid_event_is_handled()
    {
        Mail::fake();

        $order = Order::factory()->create([
            'email' => 'customer@example.com',
        ]);

        $event = new OrderPaid($order);
        $listener = new SendOrderReceipt;
        $listener->handle($event);

        Mail::assertQueued(OrderReceipt::class, function (OrderReceipt $mail) use ($order) {
            return $mail->hasTo('customer@example.com')
                && $mail->order->is($order);
        });
    }
}
