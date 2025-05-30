<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Mail\OrderReceipt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderReceipt implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderPaid $event): void
    {
        Mail::to($event->order->email)
            ->bcc([config('mail.to.address')])
            ->queue(new OrderReceipt($event->order));
    }
}
