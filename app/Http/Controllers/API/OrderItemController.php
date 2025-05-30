<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Traits\HandlesLatest;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    use HandlesLatest;

    public function index(Request $request)
    {
        $orderItems = $this->applyLatest($request, OrderItem::with(['product', 'order']))->get();

        return $orderItems;
    }
}
