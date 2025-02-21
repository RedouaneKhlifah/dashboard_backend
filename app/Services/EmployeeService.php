<?php

namespace App\Services;

use App\Models\Employee;
use App\Repositories\EmployeeRepository;

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
}
