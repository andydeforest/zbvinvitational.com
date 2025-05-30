<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Traits\HandlesLatest;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use HandlesLatest;

    public function index(Request $request)
    {

        $orders = $this->applyLatest($request, Order::with(['items.product']))->get();

        return $orders;
    }

    public function show(Order $order)
    {
        $order->load('items.product');

        return new OrderResource($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(['status' => 'deleted']);
    }
}
