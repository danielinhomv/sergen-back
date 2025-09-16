<?php

namespace App\Http\Controllers;

use App\Services\Reports\InseminationReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
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
