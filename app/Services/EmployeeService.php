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
            // Find employee by matricule
            $employee = Employee::where('matricule', $matricule)->first();
            
            if (!$employee) {
                // Skip invalid matricules
                return null;
            }            
            // Process each entry: convert time format and parse dates
            $processedEntries = $entries->map(function ($entry) {
                // Parse time ("HH:MM" to decimal hours)
                $timeParts = explode(':', $entry['presence']);
                $hours = (float)$timeParts[0];
                $minutes = (float)$timeParts[1];
                $entry['presence_decimal'] = $hours + ($minutes / 60);
                
                // Parse date ("DD/MM/YYYY" to Carbon instance)
                $entry['date_obj'] = \Carbon\Carbon::createFromFormat('d/m/Y', $entry['date']);
                
                return $entry;
            });
            
            // Group entries by date and sum presence hours for each date
            $dailyHours = $processedEntries->groupBy(function ($entry) {
                return $entry['date_obj']->format('Y-m-d');
            })->map(function ($dateEntries) {
                return $dateEntries->sum('presence_decimal');
            });

            // Calculate total hours across all dates
            $totalHours = $dailyHours->sum();
            
            // Get date range
            $dates = $processedEntries->pluck('date_obj')->sort();
            $startDate = $dates->first()->format('Y-m-d');
            $endDate = $dates->last()->format('Y-m-d');
            

            // Calculate total pay
            $totalGain = $totalHours * (float)$employee->price_per_hour;
            
            // Return summarized data for this employee
            return [
                'employee_id' => $employee->id,
                "price_per_hour" => $employee->price_per_hour,
                'matricule' => $matricule,
                'name' => $employee->name ?? 'Unknown',
                'total_hours' => round($totalHours, 2), // Round to 2 decimal places
                'start_date' => $startDate,
                'end_date' => $endDate,
                'price_per_hour' => (float)$employee->price_per_hour,
                'total_gain' => round($totalGain, 2), // Round to 2 decimal places
                'daily_hours' => $dailyHours->toArray(), // Optional: include daily breakdown
            ];
        })
        ->filter() // Remove null entries (invalid matricules)
        ->values() // Re-index array
        ->all();
    }
    
    public function storeEmployeePayHistory(array $processedData)
    {
        Log::info('Storing pay history', $processedData);
        return $this->repository->storeHistory($processedData);
    }
}
