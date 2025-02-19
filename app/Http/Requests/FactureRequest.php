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
            'order_id' => 'nullable|exists:orders,id',
            "client_id" => 'required|exists:clients,id',
            'reference' => 'required|string|max:255',
            'facture_date' => 'required|date',
            'expiration_date' => 'required|date',
            "paid_amount" => 'nullable|numeric',
            'tva' => 'required|numeric',
            'remise_type' => 'required|string|max:255 |in:PERCENT,FIXED',
            'remise' => 'nullable|numeric',
            'note' => 'nullable|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.price_unitaire' => 'required|numeric',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.order_id' => 'nullable|exists:orders,id',
        ];
    }
}