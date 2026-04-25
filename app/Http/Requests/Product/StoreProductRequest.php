<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:220', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'images' => ['nullable', 'array'],
            'images.*' => ['url'],
            'attributes' => ['nullable', 'array'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'views_count' => ['integer', 'min:0'],
            'avg_rating' => ['numeric', 'min:0', 'max:5'],
            'reviews_count' => ['integer', 'min:0']

        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Le champ catégorie est obligatoire.',
            'category_id.exists' => 'La catégorie sélectionnée est invalide.',
            'name.required' => 'Le nom du produit est obligatoire.',
            'name.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'name.max' => 'Le nom du produit ne peut pas dépasser 200 caractères.',
            'slug.required' => 'Le slug du produit est obligatoire.',
            'slug.string' => 'Le slug du produit doit être une chaîne de caractères.',
            'slug.max' => 'Le slug du produit ne peut pas dépasser 220 caractères.',
            'slug.unique' => 'Ce slug est déjà utilisé pour un autre produit.',
            'price.required' => 'Le prix du produit est obligatoire.',
            'price.numeric' => 'Le prix du produit doit être un nombre.',
            'price.min' => 'Le prix du produit doit être au moins 0.',
            'compare_price.numeric' => 'Le prix barré doit être un nombre.',
            'compare_price.min' => 'Le prix barré doit être au moins 0.',
            'stock_quantity.required' => 'La quantité en stock est obligatoire.',
            'stock_quantity.integer' => 'La quantité en stock doit être un entier.',
            'stock_quantity.min' => 'La quantité en stock doit être au moins 0.',
            'sku.string' => 'Le code produit doit être une chaîne de caractères.',
            'sku.max' => 'Le code produit ne peut pas dépasser 100 caractères.',
            'sku.unique' => 'Ce code produit est déjà utilisé pour un autre produit.',
            'images.array' => 'Les images doivent être un tableau.',
            'images.*.url' => 'Chaque image doit être une URL valide.',
            'attributes.array' => 'Les attributs doivent être un tableau.',
            'is_active.boolean' => 'Le champ actif doit être un booléen.',
            'is_featured.boolean' => 'Le champ en vedette doit être un booléen.',
            'views_count.integer' => 'Le nombre de vues doit être un entier.',
            'views_count.min' => 'Le nombre de vues doit être au moins 0.',
            'avg_rating.numeric' => 'La note moyenne doit être un nombre.',
            'avg_rating.min' => 'La note moyenne doit être au moins 0.',
            'avg_rating.max' => 'La note moyenne ne peut pas dépasser 5.',
            'reviews_count.integer' => 'Le nombre d\'avis doit être un entier.',
            'reviews_count.min' => 'Le nombre d\'avis doit être au moins 0.',
        ];
    }
}
