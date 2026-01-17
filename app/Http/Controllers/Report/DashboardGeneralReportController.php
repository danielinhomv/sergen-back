<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\DashboardGeneralReportRequest;
use App\Services\Report\ReportRunner;
use App\Services\Report\Strategies\DashboardGeneralReportStrategy;

class DashboardGeneralReportController extends Controller
{
    public function __construct(
        private readonly ReportRunner $runner,
        private readonly DashboardGeneralReportStrategy $strategy,
    ) {}

    public function index(DashboardGeneralReportRequest $request)
    {
        $result = $this->runner->run($this->strategy, $request->filters());
        return response()->json($result['payload'], $result['status']);
    }
}
