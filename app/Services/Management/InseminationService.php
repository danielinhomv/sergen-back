<?php

namespace App\Services\Management;

use App\Repositories\Management\ControlBovineRepository;
use App\Repositories\Management\InseminationRepository;

class InseminationService
{

    private ControlBovineRepository $controlBovineRepository;
    private InseminationRepository $inseminationRepository;

    public function __construct(ControlBovineRepository $controlBovineRepository, InseminationRepository $inseminationRepository)
    {
        $this->controlBovineRepository = $controlBovineRepository;
        $this->inseminationRepository = $inseminationRepository;
    }

    public function create($request)
    {

        try {
            $bovineControl = $this->controlBovineRepository->find($request->input('control_bovine_id'));

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

            $insemination = $this->inseminationRepository->create($request);

            if (!$insemination) {
                return ['error' => 'Failed to create Implant Retrieval'];
            }

            $inseminations = $bovineControl->inseminations;

            return
                [
                    'message' => 'insemination created successfully',
                    'insemination' => $this->toMap($inseminations)
                ];
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    private function toMap($inseminations)
    {
        try {

            return $inseminations->map(function ($insemination) {
                return $this->toMapSingle($insemination);
            });
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function all($request)
    {
        try {
            $bovineControl = $this->controlBovineRepository->find($request->input('control_bovine_id'));

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

            $inseminations = $bovineControl->inseminations;

            if (!$inseminations) {
                return ['error' => 'Inseminatios not found'];
            }
            return
                [
                    'message' => 'insemination retrievals successfully',
                    'insemination' => $this->toMap($inseminations)
                ];
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    private function toMapSingle($insemination)
    {

        $bull = $insemination->bull;

        if (!$bull) {
            return ['error' => 'Bull not Found in Array'];
        }

        return [
            'id' => $insemination->id,
            'body_condition_score' => $insemination->body_condition_score,
            'heat_quality' => $insemination->heat_cuality,
            'protocol_details' => $insemination->protocol_details,
            'observation' => $insemination->observation,
            'others' => $insemination->others,
            'date' => $insemination->date,
            'bull' => $bull->name
        ];
    }

    public function delete($request)
    {
        try {

            $insemination = $this->inseminationRepository->findById($request->input('id'));

            $insemination->delete();

            $bovineControl = $this->controlBovineRepository->find($request->input('control_bovine_id'));
            $inseminations = $bovineControl->inseminations;

            return [
                'message' => 'insemination deleted successfully',
                'insemination' => $this->toMap($inseminations)
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Exception occurred: ' . $e->getMessage()
            ];
        }
    }

    public function update($id, $request)
    {
        try {

            $insemination = $this->inseminationRepository->findById($id);

            $insemination->update($request->all());

            $bovineControl = $this->controlBovineRepository->find($request->input('control_bovine_id'));
            
            $inseminations = $bovineControl->inseminations;


            return [
                'message' => 'insemination updated successfully',
                'insemination' => $this->toMap($inseminations)
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Exception occurred: ' . $e->getMessage()
            ];
        }
    }
}
