<?php

namespace App\Services\Report;

use App\Models\Bull;
use App\Repositories\Management\BullRepository;
use App\Repositories\Report\InseminationReportRepository;
use App\Services\Report\DialogFlowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

class InseminationReportService
{
    private InseminationReportRepository $inseminationRepository;
    private BullRepository $bullRepository;
    private string $sessionId;
    private DialogFlowService $dialogFlowService;

    public function __construct(
        InseminationReportRepository $inseminationRepository,
        DialogFlowService $dialogFlowService,
        BullRepository $bullRepository
    ) {
        $this->dialogFlowService = $dialogFlowService;
        $this->inseminationRepository = $inseminationRepository;
        $this->bullRepository = $bullRepository;
    }

    public function generateReport($request): array
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

        Log::info('entrando al repositorio-----------------------------');
        $reportData = $this->inseminationRepository->processReport(
            $parameters,
            $property_id,
            $startDate,
            $endDate,
        );

        if (empty($reportData)) {
            return [
                'error' => 'No se encontraron datos para los criterios especificados.'
            ];
        }
        
        $reportData = array_map(function ($item) {
            if (isset($item['bull_id'])) {
               
                $bull = $this->bullRepository->getById($item['bull_id']);

                if ($bull) {
                    $item['bull'] = $bull->name;
                } else {
                    $item['bull'] = null;
                }
                unset($item['bull_id']);
            }

            return $item;
        }, $reportData);

        return $reportData;
    }
    
}
