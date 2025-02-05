<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PartenaireRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $partenaire = $this->route('partenaire');

        return [
            'name' => 'required|string|max:255',
            'matricule' => [
                'required',
                'string',
                Rule::unique('partenaires', 'matricule')->ignore($partenaire)
            ],
        ];
    }
}