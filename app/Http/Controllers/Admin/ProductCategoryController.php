<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductCategory\StoreProductCategoryRequest;
use App\Http\Requests\Admin\ProductCategory\UpdateProductCategoryRequest;
use App\Models\ProductCategory;

class ProductCategoryController extends AdminResourceController
{
    protected function returnsTo(): string
    {
        return 'admin.products.index';
    }

    protected function modelClass(): string
    {
        return ProductCategory::class;
    }

    protected function folder(): string
    {
        return 'categories';
    }

    protected function viewNamespace(): string
    {
        return 'Admin/ProductCategories';
    }

    protected function storeRequest(): string
    {
        return StoreProductCategoryRequest::class;
    }

    protected function updateRequest(): string
    {
        return UpdateProductCategoryRequest::class;
    }
}
