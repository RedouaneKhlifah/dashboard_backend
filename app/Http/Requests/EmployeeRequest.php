<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $employeeId = $this->route('employee') ? $this->route('employee')->id : null;

        return [
            'matricule'          => [
                'required',
                'string',
                'max:255',
                Rule::unique('employees', 'matricule')->ignore($employeeId)
            ],
            'last_name'           => 'required|string|max:255',
            'first_name'          => 'required|string|max:255',
            'national_id'         => [
                'required',
                'string',
                'max:255',
                Rule::unique('employees', 'national_id')->ignore($employeeId)
            ],
            
            'address'             => 'required|string|max:255',
            'city'                => 'required|string|max:255',
            'date_of_engagement'  => 'required|date',
            "birth_date"          => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'national_id.unique' => trans("employee.validation.national_id_already_exists"),
        ];
    }
}
