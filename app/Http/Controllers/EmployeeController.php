<?php

namespace App\Http\Controllers;

use App\Events\ModelUpdated;
use App\Http\Requests\EmployeeRequest;

use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\HistoryOfPayRequest;
use Illuminate\Support\Facades\Log;


class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(Request $request): JsonResponse
    {
        $searchTerm = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $employees = $this->employeeService->getAllEmployees($searchTerm, $perPage);
        return response()->json($employees);
    }

    public function store(EmployeeRequest $request): JsonResponse
    {
        $employee = $this->employeeService->createEmployee($request->validated());
        broadcast(new ModelUpdated($employee, 'employee', 'created'));
        return response()->json($employee, 201);
    }

    public function show(Employee $employee): JsonResponse
    {
        $employee = $this->employeeService->getEmployee($employee);
        return response()->json($employee);
    }

    public function update(EmployeeRequest $request, Employee $employee): JsonResponse
    {
        $employee = $this->employeeService->updateEmployee($employee, $request->validated());
        broadcast(new ModelUpdated($employee, 'employee', 'updated'));
        return response()->json($employee);
    }

    public function destroy(Employee $employee): JsonResponse
    {
        $this->employeeService->deleteEmployee($employee);
        broadcast(new ModelUpdated($employee, 'employee', 'deleted'));
        return response()->json(null, 204);
    }

    public function StoreHistoryOfPay(HistoryOfPayRequest $request , Employee $employee)
    {
        try {


           $hisyoryOfPay  = $this->employeeService->storeEmployeePayHistory($request->validated() , $employee);
    
            return response()->json($hisyoryOfPay, 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error processing request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getEmployeeHistoryOfPay(Employee $employee)
    {
        $employee = $employee->load('paymentHistories');

        return response()->json($employee);
    }
}
