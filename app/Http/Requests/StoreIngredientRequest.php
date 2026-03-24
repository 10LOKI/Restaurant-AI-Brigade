<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIngredientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'in:contains_meat,contains_sugar,contains_cholesterol,contains_gluten,contains_lactose',
        ];
    }
}
