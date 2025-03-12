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
            'total_ton' => 'required|numeric',
            'price_per_ton' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ];
    }
}