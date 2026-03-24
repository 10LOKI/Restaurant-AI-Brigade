<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:100',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_available'   => 'boolean',
            'category_id'    => 'required|exists:categories,id',
            'ingredient_ids' => 'nullable|array',
            'ingredient_ids.*' => 'exists:ingredients,id',
        ];
    }
}
