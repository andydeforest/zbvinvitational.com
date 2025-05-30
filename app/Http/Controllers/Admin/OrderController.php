<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Products\Types\ProductTypeRegistry;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Orders/Index', [
            'types' => array_map(fn ($type) => $type::identifier(), ProductTypeRegistry::all()),
        ]);
    }

    public function show(Order $order)
    {
        return Inertia::render('Admin/Orders/Show', [
            'order' => $order->load('items.product'),
        ]);
    }
}
