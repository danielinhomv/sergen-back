<?php
namespace App\Services\Management;

use App\Repositories\Management\BirthRepository;
use App\Repositories\Management\ControlBovineRepository;

class BirthService
{

    private ControlBovineRepository $controlBovineRepository;
    private BirthRepository $birthRepository;

    public function __construct(ControlBovineRepository $controlBovineRepository,
                                BirthRepository $birthRepository)
    {
        $this->controlBovineRepository = $controlBovineRepository;
        $this->birthRepository = $birthRepository;
    }
    
    public function create($request)
    {
        try {

            $birth = $this->birthRepository->create($request);

            if (!$birth) {
                return ['error' => 'Failed to create birth'];
            }

             return
                [
                    'message' => 'Birth created successfully',
                    'birth' => $this->toMapSingle($birth)
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

            $birth = $bovineControl->birth();

            if (!$birth) {
                return ['error' => 'Implant Retrieval not found'];
            }

            return
                [
                    'message' => 'Birth Retrieved successfully',
                    'Birth' => $this->toMapSingle($birth)
                ];

        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    private function  toMapSingle($birth)
    {
        try {

            return [
                'id' => $birth->id,
                'birthdate' => $birth->birthdate,
                'sex' => $birth->sex,
                'birth_weight' => $birth->birth_weigth,
                'rgd' => $birth->rgd,
                'type_of_birth' => $birth->type_of_birth,
                'bull' => $birth->bull()->name
            ];
 
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }
}
