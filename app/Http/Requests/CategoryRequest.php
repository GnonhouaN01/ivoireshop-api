<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categoryId = $this->route('category')?->id ?? $this->route('id'); // Permet de récupérer l'ID de la catégorie à partir de la route, que ce soit pour une mise à jour ou une création
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH') || $categoryId !== null; // Détermine si c'est une mise à jour en vérifiant la méthode HTTP ou la présence d'un ID de catégorie

        return [
            'name' => [$isUpdate ? 'nullable' : 'required', 'string', 'max:100'],
            'slug' => [
                'nullable',
                'string',
                'max:120',
                $categoryId
                    ? Rule::unique('categories', 'slug')->ignore($categoryId)
                    : Rule::unique('categories', 'slug'),
            ],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
