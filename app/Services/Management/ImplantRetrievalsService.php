<?php

namespace App\Services\Management;

use App\Repositories\Management\ControlBovineRepository;
use App\Repositories\Management\ImplantRetrievalsRepository;

class ImplantRetrievalsService
{

    private ControlBovineRepository $controlBovineRepository;
    private ImplantRetrievalsRepository $implantRetrievalsRepository;

    public function __construct(ControlBovineRepository $controlBovineRepository, ImplantRetrievalsRepository $implantRetrievalsRepository)
    {
        $this->controlBovineRepository = $controlBovineRepository;
        $this->implantRetrievalsRepository = $implantRetrievalsRepository;
    }

    public function create($request)
    {
        try {

            $implantRetrieval = $this->implantRetrievalsRepository->create($request);

            if (!$implantRetrieval) {
                return ['error' => 'Failed to create Implant Retrieval'];
            }
            return
                [
                    'message' => 'Implant_retrieval created successfully',
                    'implant_retrieval' => $this->toMapSingle($implantRetrieval)
                ];

        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function get($request)
    {
        try {
            $bovineControl = $this->controlBovineRepository->find($request->input('bovine-controls_id'));

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

            $implantRetrieval = $bovineControl->implant_retrieval;

            if (!$implantRetrieval) {
                return ['error' => 'Implant Retrieval not found'];
            }
            return
                [
                    'message' => 'Implant_retrieval retrieved successfully',
                    'implant_retrieval' => $this->toMapSingle($implantRetrieval)
                ];
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    private function  toMapSingle($implantRetrieval)
    {
        try {
            return [
                'id' => $implantRetrieval->id,
                'status' => $implantRetrieval->status,
                'work_team' => $implantRetrieval->work_team,
                'used_product_summary' => $implantRetrieval->used_product_summary,
                'date' => $implantRetrieval->date
            ];
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }
}
