<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'sometimes|string|max:100',
            'description'    => 'nullable|string',
            'price'          => 'sometimes|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_available'   => 'boolean',
            'category_id'    => 'sometimes|exists:categories,id',
            'ingredient_ids' => 'nullable|array',
            'ingredient_ids.*' => 'exists:ingredients,id',
        ];
    }
}
