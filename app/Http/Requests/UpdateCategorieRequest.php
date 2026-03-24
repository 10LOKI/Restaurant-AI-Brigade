<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategorieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('category')?->id;

        return [
            'name'        => "sometimes|string|max:100|unique:categories,name,{$id}",
            'description' => 'nullable|string',
            'color'       => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_active'   => 'boolean',
        ];
    }
}
