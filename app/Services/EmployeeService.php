<?php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use Illuminate\Support\Facades\Log;

class EmployeeService
{
    protected $repository;

    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllEmployees($searchTerm = null, $perPage = 10)
    {
        return $this->repository->getAllWithSearch($searchTerm, $perPage);
    }

    public function getEmployee(Employee $employee)
    {
        return $this->repository->find($employee);
    }

    public function createEmployee(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateEmployee(Employee $employee, array $data)
    {
        return $this->repository->update($employee, $data);
    }

    public function deleteEmployee(Employee $employee)
    {
        return $this->repository->delete($employee);
    }

    public function processPayData(array $data): array
    {
        $collection = collect($data);
        
        // Group by employee matricule
        $grouped = $collection->groupBy('matricule');

        return $grouped->map(function ($entries, $matricule) {
            $employee = Employee::where('matricule', $matricule)->first();
            
            if (!$employee) {
                return null; // Return null for invalid matricules
            }

            $dates = $entries->pluck('date')->sort();
            
            return [
                'employee_id' => $employee->id,
                'total_hours' => $entries->sum('presence'),
                'start_date' => $dates->first(),
                'end_date' => $dates->last(),
                'total_gain' => $entries->sum(fn($entry) => (float) $entry['presence']) * (float) $employee->price_per_hour,
            ];
        })->filter()->values()->all(); // Filter out null values
    }

    public function storeEmployeePayHistory(array $processedData)
    {
        return $this->repository->storeHistory($processedData);
    }
}
