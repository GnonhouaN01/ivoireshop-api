<?php

namespace App\Http\Requests\Order;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'payment_method' => ['required', 'string', 'in:card,bank_transfer,mobile_money,wallet'],
            'delivery_fee' => ['nullable', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'], // Instructions spéciales pour la livraison ou le vendeur
            'customer_surname' => ['required', 'string', 'max:255'],
            'customer_phone_number' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'array'],// Adresse de livraison structurée
            'shipping_address.address' => ['required', 'string', 'max:500'], // Rue, numéro, etc.
            'shipping_address.city' => ['required', 'string', 'max:100'], // Ville
            'shipping_address.zip_code' => ['required', 'string', 'max:20'],
            'shipping_address.country' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => 'Le champ méthode de paiement est obligatoire.',
            'payment_method.string' => 'La méthode de paiement doit être une chaîne de caractères.',
            'payment_method.in' => 'La méthode de paiement doit être l\'une des suivantes : card, bank_transfer, mobile_money, wallet.',
            'delivery_fee.numeric' => 'Les frais de livraison doivent être un nombre.',
            'delivery_fee.min' => 'Les frais de livraison ne peuvent pas être négatifs.',
            'discount_amount.numeric' => 'Le montant de la réduction doit être un nombre.',
            'discount_amount.min' => 'Le montant de la réduction ne peut pas être négatif.',
            'notes.string' => 'Les notes doivent être une chaîne de caractères.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères.',
            'customer_surname.required' => 'Le nom du client est obligatoire.',
            'customer_surname.string' => 'Le nom du client doit être une chaîne de caractères.',
            'customer_surname.max' => 'Le nom du client ne peut pas dépasser 255 caractères.',
            'customer_phone_number.required' => 'Le numéro de téléphone du client est obligatoire.',
            'customer_phone_number.string' => 'Le numéro de téléphone du client doit être une chaîne de caractères.',
            'customer_phone_number.max' => 'Le numéro de téléphone du client ne peut pas dépasser 20 caractères.',
            'shipping_address.required' => 'L\'adresse de livraison est obligatoire.',
            'shipping_address.array' => 'L\'adresse de livraison doit être un tableau structuré.',
            'shipping_address.address.required' => 'Le champ adresse dans l\'adresse de livraison est obligatoire.',
            'shipping_address.address.string' => 'Le champ adresse dans l\'adresse de livraison doit être une chaîne de caractères.',
            'shipping_address.address.max' => 'Le champ adresse dans l\'adresse de livraison ne peut pas dépasser 500 caractères.',
            'shipping_address.city.required' => 'Le champ ville dans l\'adresse de livraison est obligatoire.',
            'shipping_address.city.string' => 'Le champ ville dans l\'adresse de livraison doit être une chaîne de caractères.',
            'shipping_address.city.max' => 'Le champ ville dans l\'adresse de livraison ne peut pas dépasser 100 caractères.',
            'shipping_address.zip_code.required' => 'Le champ code postal dans l\'adresse de livraison est obligatoire.',
            'shipping_address.zip_code.string' => 'Le champ code postal dans l\'adresse de livraison doit être une chaîne de caractères.',
            'shipping_address.zip_code.max' => 'Le champ code postal dans l\'adresse de livraison ne peut pas dépasser 20 caractères.',
            'shipping_address.country.required' => 'Le champ pays dans l\'adresse   de livraison est obligatoire.',
            'shipping_address.country.string' => 'Le champ pays dans l\'adresse de livraison doit être une chaîne de caractères.',
            'shipping_address.country.max' => 'Le champ pays dans l\'adresse de livraison ne peut pas dépasser 100 caractères.',
        ];
    }
}
