<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class GetPartenaireTicketsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ];
    }
}