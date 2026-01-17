<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ReportFilterRequest;
use App\Services\Report\ReportRunner;
use App\Services\Report\Strategies\BirthReportStrategy;
use Barryvdh\DomPDF\Facade\Pdf;

class BirthReportController extends Controller
{
    public function __construct(
        private readonly ReportRunner $runner,
        private readonly BirthReportStrategy $strategy
    ) {}

    public function index(ReportFilterRequest $request)
    {
        $result = $this->runner->run($this->strategy, $request->filters());
        return response()->json($result['payload'], $result['status']);
    }

    public function export(ReportFilterRequest $request)
    {
        // Ejecuta la lógica existente para obtener los datos
        $result = $this->runner->run($this->strategy, $request->filters());

        if (!$result['payload']['success']) {
            return response()->json($result['payload'], $result['status']);
        }

        // Extrae los datos del JSON
        $data = $result['payload']['data'];
        $generatedAt = $result['payload']['meta']['generated_at'] ?? now()->toIso8601String();

        // Genera el PDF en landscape completo
        $pdf = Pdf::loadView('reports.birth', compact('data', 'generatedAt'));
        $pdf->setPaper('A4', 'landscape'); // Todo en horizontal

        $pdf->setOptions([
            'defaultFont' => 'Helvetica', // Fuente seria
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultPaperSize' => 'a4',
            'defaultPaperOrientation' => 'landscape',
        ]);

        // Descarga el PDF
        $filename = 'informe-birth-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}
