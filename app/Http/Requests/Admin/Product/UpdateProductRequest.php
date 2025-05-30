<?php

namespace App\Http\Requests\Admin\Product;

class UpdateProductRequest extends ProductFormRequest
{
    public function rules(): array
    {
        return $this->baseRules();
    }
}
