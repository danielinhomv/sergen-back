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
        return DB::transaction(function () use ($request) {
            try {
                // 1. Crear el Bovino (el hijo/cría)
                $bovineData = [
                    'birthdate'   => $request->input('birthdate'),
                    'sex'         => $request->input('sex'),
                    'weight'      => $request->input('birth_weight'),
                    'rgd'         => $request->input('rgd'),
                    'property_id' => $request->input('property_id'),
                    'mother_id'   => $request->input('bovine_id'), // ID de la madre
                ];

                //validar que la rgd no exista en algunos de los bovinos de la propiedad
                $existingBovine = $this->bovineRepository->existRgd($bovineData['rgd'], $bovineData['property_id']);
                if ($existingBovine) {
                    return ['error' => 'Rgd already exists for another bovine in the property'];
                }

                $bovine = $this->bovineRepository->createRaw($bovineData);

                // 2. Crear el Parto asociado al hijo y al control
                $birthData = [
                    'type_of_birth'     => $request->input('type_of_birth'),
                    'control_bovine_id' => $request->input('control_bovine_id'),
                    'bovine_id'         => $bovine->id, // Relacionamos con el nuevo bovino
                ];

                $birth = $this->birthRepository->createRaw($birthData);

                return [
                    'status'  => 'success',
                    'message' => 'Bovino y Parto creados correctamente',
                    'data'    => $this->toMapSingle($birth, null, $bovine)
                ];
            } catch (Exception $e) {
                // El rollback es automático en DB::transaction si hay una excepción
                return ['error' => 'Exception occurred: ' . $e->getMessage()];
            }
        });
    }

    public function get($request)
    {
        try {
            $bovineControl = $this->controlBovineRepository->find($request->input('control_bovine_id'));

            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

            // Obtener el toro padre a través de la relación de inseminación
            $bullFather = $bovineControl->first_insemination->bull ?? null;

            // Obtener el registro del parto
            $birth = $bovineControl->birth; // Asumiendo relación HasOne en el modelo

            if (!$birth) {
                return ['error' => 'Birth record not found'];
            }

            // IMPORTANTE: Obtenemos el bovino (cría) asociado al parto
            $bovine = $birth->bovine; 

            return [
                'status' => 'success',
                'message' => 'Birth Retrieved successfully',
                'data' => $this->toMapSingle($birth, $bullFather, $bovine)
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

    private function toMapSingle($birth, $bullFather = null, $bovine = null)
    {
        // Validamos que el objeto bovino exista para evitar errores de null
        return [
            'id'            => $birth->id,
            'birthdate'     => $bovine ? $bovine->birthdate : null,
            'sex'           => $bovine ? $bovine->sex : null,
            'birth_weight'  => $bovine ? $bovine->weight : null,
            'rgd'           => $bovine ? $bovine->rgd : null,
            'bull_father'   => $bullFather->name ?? 'Unknown',
            'type_of_birth' => $birth->type_of_birth,
        ];
    }
}