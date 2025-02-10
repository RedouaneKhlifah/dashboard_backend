<?php

namespace App\Http\Requests;

use App\Enums\TicketEnums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'matricule' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'client_id' => 'required_if:status,' . StatusEnum::EXIT->value . '|nullable|exists:clients,id',
            'number_prints' => 'required|integer|min:1',
            'poids_brut' => 'required|numeric|min:0',
            'poids_tare' => 'required|numeric|min:0',
            'status' => 'required|in:ENTRY,EXIT',
        ];
    }

    protected function prepareForValidation()
    {
        // If the status is ENTRY and client_id is provided, set client_id to null
        if ($this->status === StatusEnum::ENTRY->value) {
            $this->merge(['client_id' => null]);
        }
    }
}
