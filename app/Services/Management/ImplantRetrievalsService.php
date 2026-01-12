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
            $bovineControl = $this->controlBovineRepository->find($request->input('control_bovine_id'));

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

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
            $bovineControl = $this->controlBovineRepository->find($request->input('control_bovine_id'));

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

            $implantRetrieval = $bovineControl->implant_retrieval;

            if (!$implantRetrieval) {
                //si no hay datos devolvemos null
                return [
                    'message' => 'No Implant_retrieval data found',
                    'implant_retrieval' => null
                ];}
            return
                [
                    'message' => 'Implant_retrieval retrieved successfully',
                    'implant_retrieval' => $this->toMapSingle($implantRetrieval)
                ];

        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function update($request)
    {
        try {
            $implantRetrieval = $this->implantRetrievalsRepository->update($request);

            if (!$implantRetrieval) {
                return ['error' => 'Failed to update Implant Retrieval'];
            }

            return
                [
                    'message' => 'Implant_retrieval updated successfully',
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
                'used_products_summary' => $implantRetrieval->used_products_summary,
                'date' => $implantRetrieval->date
            ];  
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }
}
