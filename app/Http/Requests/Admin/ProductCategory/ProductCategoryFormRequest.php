<?php

namespace App\Http\Requests\Admin\ProductCategory;

use Illuminate\Foundation\Http\FormRequest;

abstract class ProductCategoryFormRequest extends FormRequest
{
    protected function baseRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['sometimes', 'nullable', 'image', 'max:2048'],
        ];
    }
}
