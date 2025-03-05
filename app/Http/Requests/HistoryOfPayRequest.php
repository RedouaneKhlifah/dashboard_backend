<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class HistoryOfPayRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            '*.matricule' => [
                'required',
                'string'
            ],
            '*.date' => 'required|date_format:d/m/Y',
            '*.presence' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            '*.matricule.exists' => 'The matricule :input does not exist in our records',
            '*.date.date' => 'Invalid date format for :input',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new HttpResponseException(response()->json([
            'message' => $errors[0], // Return only the first error message
        ], 422));
    }
}