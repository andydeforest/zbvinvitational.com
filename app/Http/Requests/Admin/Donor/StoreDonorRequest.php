<?php

namespace App\Http\Requests\Admin\Donor;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'donors' => ['required', 'array', 'min:1'],
            'donors.*.name' => ['required', 'string', 'max:255'],
        ];
    }
}
