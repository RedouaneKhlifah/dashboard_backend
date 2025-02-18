<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FactureRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'order_id' => 'nullable|exists:tickets,id',
            "client_id" => 'required|exists:clients,id',
            'reference' => 'required|string|max:255',
            'expiration_date' => 'required|date',
            'tva' => 'required|numeric',
            'remise_type' => 'required|string|max:255 |in:PERCENT,FIXED',
            'remise' => 'nullable|numeric',
            'note' => 'nullable|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.price_unitaire' => 'required|numeric',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }
}