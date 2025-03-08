<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\HistoryOfPay;
use Illuminate\Support\Facades\Log;

class EmployeeRepository
{
    protected $model;

    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    public function getAllWithSearch($searchTerm = null, $perPage = 10)
    {
        $query = $this->model->newQuery();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('national_id', 'like', "%{$searchTerm}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function find(Employee $employee)
    {
        return $employee;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Employee $employee, array $data)
    {
        $employee->update($data);
        return $employee;
    }

    public function delete(Employee $employee)
    {
        return $employee->delete();
    }

    public function storeHistory(array $processedData)
    {
        Log::info('Storing history of pay for employees', $processedData);
        foreach ($processedData as $data) {
            HistoryOfPay::updateOrCreate(
                [
                    'employee_id' => $data['employee_id'],
                    "price_per_hour" => $data['price_per_hour'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    "total_hours" => $data['total_hours'],
                    "total_gain" => $data['total_gain'],
                ],
                $data
            );
        }
    }
}
