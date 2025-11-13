<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use BovineReportService;
use Illuminate\Http\Request;

class BovineReportController extends Controller
{
    protected $bovineReportService;

    public function __construct(BovineReportService $bovineReportService)
    {
        $this->bovineReportService = $bovineReportService;
    }

    public function generateBovineHistoryReport(Request $request)
    {
        $bovine_id = $request->input('bovine_id');
        $property_id = $request->input('property_id');

        $report = $this->bovineReportService->generateBovineHistoryReport($bovine_id, $property_id);
        if (isset($report['error'])) {
            return response()->json(['error' => $report['error']], 400);
        }
        return response()->json($report);
    }

    public function generatePropertyBovineHistoryReport(Request $request)
    {
        $property_id = $request->input('property_id');          
        $report = $this->bovineReportService->generatePropertyBovineHistoryReport($property_id);
     
        if (isset($report['error'])) {
            return response()->json(['error' => $report['error']], 400);                
        }
        return response()->json($report);
    }

}
