<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Carbon\Carbon;


class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request, $start_date = null, $end_date = null)
    {
        $endDate = $end_date ? Carbon::parse($end_date) : Carbon::today();
        $startDate = $start_date ? Carbon::parse($start_date) : Carbon::parse($endDate)->subMonth();


        $data = $this->dashboardService->getDashboardData($startDate, $endDate);

        return response()->json($data);
    }
}