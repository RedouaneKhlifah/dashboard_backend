<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reason' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:CREDIT,DEBIT',
            'note' => 'nullable|string|max:255',
        ];
    }
}
