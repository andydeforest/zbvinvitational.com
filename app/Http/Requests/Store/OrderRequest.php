<?php

namespace App\Http\Requests\Store;

use App\Models\Product;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'billing.firstName' => 'required|string',
            'billing.lastName' => 'required|string',
            'billing.address' => 'required|string',
            'billing.city' => 'required|string',
            'billing.state' => 'required|string',
            'billing.zip' => 'required|string',
            'billing.phone' => 'required|string',
            'billing.email' => 'required|email',
            'billing.notes' => 'nullable|string',
            'cart' => 'required|array|min:1',
            'cart.*.product.id' => 'required|integer|exists:products,id',
            'cart.*.product.price' => 'required|numeric',
            'cart.*.product.metadata' => 'nullable|array',
            'cart.*.quantity' => 'required|integer|min:1',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors(),
        ], 422));
    }

    /**
     * @return array{
     *     firstName:string,
     *     lastName:string,
     *     address:string,
     *     city:string,
     *     state:string,
     *     zip:string,
     *     phone:string,
     *     email:string,
     *     notes?:string
     * }
     */
    public function billing(): array
    {
        return $this->validated()['billing'];
    }

    /**
     * @return array<int, array{product: array{id:int,price:float,metadata?:array},quantity:int}>
     */
    public function cartItems(): array
    {
        return $this->validated()['cart'];
    }
}
