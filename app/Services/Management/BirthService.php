<?php

namespace App\Services\Management;

use App\Repositories\Management\BirthRepository;
use App\Repositories\Management\BovineRepository;
use App\Repositories\Management\ControlBovineRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class BirthService
{
    private ControlBovineRepository $controlBovineRepository;
    private BirthRepository $birthRepository;
    private BovineRepository $bovineRepository;

    public function __construct(
        ControlBovineRepository $controlBovineRepository,
        BirthRepository $birthRepository,
        BovineRepository $bovineRepository
    ) {
        $this->controlBovineRepository = $controlBovineRepository;
        $this->birthRepository = $birthRepository;
        $this->bovineRepository = $bovineRepository;
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {

            $controlBovineId = $request->input('control_bovine_id');

            // 1. Bloquear el control bovino (evita partos duplicados)
            $bovineControl = $this->controlBovineRepository
                ->lockForUpdate($controlBovineId);

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

            if ($bovineControl->birth) {
                return ['error' => 'Birth record already exists for this Bovine Control'];
            }

            $typeOfBirth = $request->input('type_of_birth');

            // 2. Crear cría solo si nació viva
            if (in_array($typeOfBirth, ['normal', 'premeture'])) {

                // 2.1 Validar RGD dentro de la transacción
                $rgdExists = $this->bovineRepository->existRgd(
                    $request->input('rgd'),
                    $request->input('property_id')
                );

                if ($rgdExists) {
                    return ['error' => 'Rgd already exists for another bovine in the property'];
                }

                $bovineData = [
                    'birthdate'   => $request->input('birthdate'),
                    'sex'         => $request->input('sex'),
                    'weight'      => $request->input('birth_weight'),
                    'rgd'         => $request->input('rgd'),
                    'property_id' => $request->input('property_id'),
                    'mother_id'   => $bovineControl->bovine_id,
                ];

                $bovine = $this->bovineRepository->createRaw($bovineData);

                if (!$bovine) {
                    throw new \Exception('Failed to create bovine');
                }
            } else {
                // Abort o stillbirth → no hay cría
                $bovine = null;
            }

            // 3. Crear el parto
            $birthData = [
                'type_of_birth'     => $typeOfBirth,
                'control_bovine_id' => $controlBovineId,
                'bovine_id'         => $bovine ? $bovine->id : null,
            ];

            //busca de la 

            $birth = $this->birthRepository->createRaw($birthData);

            if (!$birth) {
                throw new \Exception('Failed to create birth record');
            }

            DB::commit();

            return [
                'message' => 'Birth registered successfully',
                'birth'   => $this->toMapSingle($birth, null, $bovine)
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'error'  => 'Transaction failed',
                'detail' => $e->getMessage()
            ];
        }
    }




    public function get($request)
    {
        try {

            $controlBovineId = $request->input('control_bovine_id');

            $bovineControl = $this->controlBovineRepository->find($controlBovineId);

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }


            $birth = $bovineControl->birth;

            if (!$birth) {
                return [
                    'message' => 'No Birth record found for this Bovine Control',
                    'birth' => null
                ];
            }

            // IMPORTANTE: Obtenemos el bovino (cría) asociado al parto
            $bovine = $this->bovineRepository->find($birth->bovine_id);

            $bullFatherName = $this->birthRepository->getBullNameByControlBovineId($controlBovineId);
            
            if (!$bullFatherName) {
                return [
                    'error' => 'no se detecto un padre'
                ];
            }
            
            return [
                'message' => 'Birth Retrieved successfully',
                'birth' => $this->toMapSingle($birth, $bullFatherName, $bovine)
            ];

        } catch (Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function update($request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $birth = $this->birthRepository->update($request);

                if (!$birth) {
                    return ['error' => 'Failed to update birth'];
                }

                // Si en el request vienen datos de la cría, actualizamos el bovino asociado
                $bovine = $birth->bovine;
                if ($bovine) {
                    $bovine->update([
                        'birthdate' => $request->input('birthdate', $bovine->birthdate),
                        'sex'       => $request->input('sex', $bovine->sex),
                        'weight'    => $request->input('birth_weight', $bovine->weight),
                        'rgd'       => $request->input('rgd', $bovine->rgd),
                    ]);
                }

                return [
                    'status' => 'success',
                    'message' => 'Birth updated successfully',
                    'birth' => $this->toMapSingle($birth, null, $bovine)
                ];
            } catch (Exception $e) {
                return ['error' => 'Exception occurred: ' . $e->getMessage()];
            }
        });
    }

    private function toMapSingle($birth, $bullFatherName = null, $bovine = null)
    {
        // Validamos que el objeto bovino exista para evitar errores de null
        return [
            'id'            => $birth->id,
            'birthdate'     => $bovine ? $bovine->birthdate : null,
            'sex'           => $bovine ? $bovine->sex : null,
            'birth_weight'  => $bovine ? $bovine->weight : null,
            'rgd'           => $bovine ? $bovine->rgd : null,
            'bull_father'   => $bullFatherName ?? 'solo se mostrara en el parto',
            'type_of_birth' => $birth->type_of_birth,
        ];
    }
}
