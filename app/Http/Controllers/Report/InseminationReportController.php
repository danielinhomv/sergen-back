<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\Report\InseminationReportService;
use Illuminate\Http\Request;

class InseminationReportController extends Controller
{
    protected InseminationReportService $reportService;

    public function __construct(InseminationReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function getInseminationReport(Request $request)
    {

        $report = $this->reportService->generateReport($request);

        if (isset($report['error'])) {
            return response()->json(['error' => $report['error']], 400);
        }

        return response()->json($report);
    }
}
