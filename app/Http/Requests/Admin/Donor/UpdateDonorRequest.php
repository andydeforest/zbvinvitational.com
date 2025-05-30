<?php

namespace App\Http\Requests\Admin\Donor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDonorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'donors' => ['present', 'array'],
            'donors.*.name' => ['required', 'string', 'max:255'],
            'donors.*.id' => ['sometimes', 'integer', 'exists:donors,id'],
            'deleted' => ['nullable', 'array'],
            'deleted.*' => ['integer', 'exists:donors,id'],
        ];
    }
}
