<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

abstract class ProductFormRequest extends FormRequest
{
    protected function baseRules(): array
    {
        return [
            'product_category_id' => ['nullable', 'exists:product_categories,id'],
            'name' => ['required', 'string'],
            'short_name' => ['required', 'string'],
            'allow_custom_price' => ['required', 'boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'is_active' => ['required', 'boolean'],
            'metadata' => ['nullable', 'array'],
            'cover_image' => ['sometimes', 'nullable', 'image', 'max:2048'],
        ];
    }
}
