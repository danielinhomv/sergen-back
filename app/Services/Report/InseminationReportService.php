<?php

namespace App\Services\Report;

use App\Repositories\Report\InseminationReportRepository;
use Illuminate\Http\Request;


class InseminationReportService
{
    private InseminationReportRepository $inseminationRepository;
    private string $sessionId; 
    private DialogFlowService $dialogFlowService;

    public function __construct(
        InseminationReportRepository $inseminationRepository,
        DialogFlowService $dialogFlowService
    ) {
        $this->dialogFlowService = $dialogFlowService;
        $this->inseminationRepository = $inseminationRepository;
    }

    public function generateReport(Request $request): array
    {
        // Carga las credenciales del archivo JSON
        $credentialsResult = $this->dialogFlowService->loadCredentials();
        if (isset($credentialsResult['error'])) {
            return $credentialsResult;
        }

        // Extrae los datos del Request HTTP
        $text = $request->input('text');
        $property_id = $request->input('property_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $userId = $request->input('user_id');


        $this->sessionId = $userId ?? 'default_session';
        if (is_null($property_id)) {
            return [
                'error' => 'El ID de control es requerido.'
            ];
        }
        if (is_null($userId)) {
            return [
                'error' => 'El ID del usuario es requerido.'
            ];
        }

        $this->sessionId = "sesion-" . $userId;

        $parameters = $this->dialogFlowService->reportRequest($text, $this->sessionId);

        if (isset($parameters['error'])) {
            return $parameters;
        }

        $reportData = $this->inseminationRepository->processReport(
            $parameters,
            $startDate,
            $endDate,
            $property_id
        );

        if (empty($reportData)) {
            return [
                'error' => 'No se encontraron datos para los criterios especificados.'
            ];
        }
        return $reportData;
    }
}
