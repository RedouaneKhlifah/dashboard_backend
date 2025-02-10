<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'display_on_desktop' => 'boolean',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'sale_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'tax' => 'nullable|numeric|min:0',
            'images'             => 'nullable|array',
            'images.*'           => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock' => 'required|numeric|min:0',
            'reorder_point' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // If `display_on_desktop` is true, set `unit` to 'kg'
        if ($this->display_on_desktop) {
            $this->merge([
                'unit' => 'kg',
            ]);
        }
    }
}