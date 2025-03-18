<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $clientId = $this->route('client');
        
        return [
            'company_ice' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clients', 'company_ice')->ignore($clientId),
            ],            
            'email' => [
                'required',
                'email',
                Rule::unique('clients', 'email')->ignore($clientId)
            ],
            'phone' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => trans("client.validation.email_already_exists"),
            'company_ice.unique' => trans("client.validation.company_ice_already_exists"),
        ];
    }
}