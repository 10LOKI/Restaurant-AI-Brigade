<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dietary_tags'   => 'required|array',
            'dietary_tags.*' => 'in:vegan,no_sugar,no_cholesterol,gluten_free,no_lactose',
        ];
    }
}
