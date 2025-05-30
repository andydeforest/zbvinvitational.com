<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Products\Types\ProductTypeRegistry;

class ProductController extends AdminResourceController
{
    protected function options(): array
    {
        return [
            'categories' => ProductCategory::all(['id', 'name']),
            'types' => array_map(fn ($type) => $type::identifier(), ProductTypeRegistry::all()),
        ];
    }

    protected function returnsTo(): string
    {
        return 'admin.products.index';
    }

    protected function modelClass(): string
    {
        return Product::class;
    }

    protected function folder(): string
    {
        return 'products';
    }

    protected function viewNamespace(): string
    {
        return 'Admin/Products';
    }

    protected function storeRequest(): string
    {
        return StoreProductRequest::class;
    }

    protected function updateRequest(): string
    {
        return UpdateProductRequest::class;
    }
}
