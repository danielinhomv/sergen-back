<?php

namespace App\Services\Report;

use App\Repositories\Report\InseminationReportRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;

class InseminationReportService
{
    protected InseminationReportRepository $inseminationRepository;

    // Propiedades de la clase
    private string $sessionId; // Puedes usar el ID del usuario o de la sesión
    protected DialogFlowService $dialogFlowService;

    public function __construct(
        InseminationReportRepository $inseminationRepository,
        DialogFlowService $dialogFlowService
    ) {
        $this->dialogFlowService = $dialogFlowService;
        $this->inseminationRepository = $inseminationRepository;
    }

    /**
     * Genera un reporte de inseminación a partir de una solicitud de webhook de Dialogflow.
     *
     * @param Request $request La solicitud HTTP completa.
     * @return array Los datos del reporte o un array con un mensaje de error.
     */
    public function generateReport(Request $request): array
    {
        // Carga las credenciales del archivo JSON
        $credentialsResult = $this->dialogFlowService->loadCredentials();
        if (isset($credentialsResult['error'])) {
            return $credentialsResult;
        }

        // Extrae los datos del Request HTTP
        $text = $request->input('text');
        $controlId = $request->input('control_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $userId = $request->input('user_id');

        $this->sessionId = $userId ?? 'default_session';
        if (is_null($controlId)) {
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
            $controlId
        );

        return $reportData;
    }
}
