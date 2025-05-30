<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index()
    {

        $categories = ProductCategory::with(['products' => function ($q) {
            $q->where('is_active', true)
                ->orderBy('name');
        }])
            ->whereHas('products', fn ($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();

        $uncategorized = Product::whereNull('product_category_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Public/Shop/Index', [
            'categories' => $categories,
            'uncategorized' => $uncategorized,
        ]);
    }

    public function checkout()
    {
        return Inertia::render('Public/Shop/Checkout');
    }

    public function confirmation(string $publicId)
    {
        $order = Order::with('items.product')->where('public_id', $publicId)->firstOrFail();

        return Inertia::render('Public/Shop/Confirmation', ['order' => $order]);
    }
}
