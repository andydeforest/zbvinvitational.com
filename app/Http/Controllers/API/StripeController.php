<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\OrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\ProductMetadataService;
use App\Services\StripeGateway;
use Illuminate\Support\Facades\DB;

class StripeController extends Controller
{
    public function __construct(
        private ProductMetadataService $productMetadataService,
        private StripeGateway $stripe
    ) {}

    public function create(OrderRequest $request)
    {
        /**
         * @var array<int, array{
         *   product: array{id:int, price:float, metadata?:array<string,mixed>},
         *   quantity:int
         * }> $cart
         */
        $cart = $request->cartItems();
        $billing = $request->billing();

        $amount = collect($cart)->reduce(
            /**
             * @param  array{product: array{price: float}, quantity: int}  $item
             * @param  int|string  $key
             */
            function (float $carry, array $item, $key): float {
                return $carry + ($item['product']['price'] * $item['quantity']);
            },
            0.0
        );

        $intent = $this->stripe->createPaymentIntent([
            'amount' => intval($amount),
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        /** @var array<int, array<string, mixed>> $metadata */
        $metadata = $request->input('metadata', []);

        $order = DB::transaction(function () use ($billing, $cart, $amount, $intent, $metadata) {
            $order = Order::create([
                'status' => 'pending',
                'total_cents' => $amount,
                'first_name' => $billing['firstName'],
                'last_name' => $billing['lastName'],
                'address' => $billing['address'],
                'city' => $billing['city'],
                'state' => $billing['state'],
                'zip' => $billing['zip'],
                'phone' => $billing['phone'],
                'email' => $billing['email'],
                'notes' => $billing['notes'],
                'stripe_payment_intent_id' => $intent->id,
            ]);

            foreach ($cart as $index => $item) {
                $product = Product::findOrFail($item['product']['id']);
                $unit_price_cents = (int) $product->price;

                if ($product->allow_custom_price) {
                    $unit_price_cents = intval($item['product']['price']);
                }

                /** @var OrderItem $orderItem */
                $orderItem = $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price_cents' => $unit_price_cents,
                ]);

                $entryMeta = $metadata[$index] ?? [];

                $filteredMetadata = $this->productMetadataService->handle($orderItem, $entryMeta);
                $orderItem->metadata = $filteredMetadata;
                $orderItem->save();
            }

            return $order;
        });

        $this->stripe->updatePaymentIntent($intent->id, [
            'metadata' => ['orderId' => $order->id],
        ]);

        return response()->json([
            'orderId' => $order->public_id,
            'clientSecret' => $intent->client_secret,
        ]);
    }
}
