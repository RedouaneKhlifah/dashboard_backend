<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;


class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request): JsonResponse
    {
        $endDate = $request->input('end_date', Carbon::today());
        $startDate = $request->input('start_date', Carbon::parse($endDate)->subMonth());

        $data = $this->dashboardService->getDashboardData($startDate, $endDate);

        return response()->json($data);
    }
}