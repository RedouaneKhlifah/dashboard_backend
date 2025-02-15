<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DevisRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ticket_id' => 'nullable|exists:tickets,id',
            "client_id" => 'required|exists:clients,id',
            'reference' => 'required|string|max:255',
            'devis_date' => 'required|date',
            'experation_date' => 'required|date',
            'tva' => 'required|numeric',
            'remise_type' => 'required|string|max:255 |in:PERCENT,FIXED',
            'remise' => 'required|numeric',
            'note' => 'nullable|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.price_unitaire' => 'required|numeric',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }
}