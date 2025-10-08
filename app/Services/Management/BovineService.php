<?php

namespace App\Services\Management;

use App\Repositories\Management\BovineRepository;
use App\Repositories\Management\PropertyRepository;
use Illuminate\Support\Facades\DB;

class BovineService
{
    private BovineRepository $bovineRepository;
    private PropertyRepository $propertyRepository;

    public function __construct(BovineRepository $bovineRepository, PropertyRepository $propertyRepository)
    {
        $this->bovineRepository = $bovineRepository;
        $this->propertyRepository = $propertyRepository;
    }

    public function all($propertyId)
    {
        $bovines = $this->getBovines($propertyId);
        return
            [
                'message' => 'Bovine retrievals successfully',
                'bovine' => $this->toMap($bovines)
            ];
    }

    private function getBovines($propertyId)
    {
        $property = $this->validarProperty($propertyId);

        return $property->bovines;
    }

    private function validarProperty($propertyId)
    {
        $property = $this->propertyRepository->findById($propertyId);

        if (!$property) {
            return ['error' => 'Property not found'];
        }
        return $property;
    }

    private function toMap($bovines)
    {
        try {
            return $bovines->map(function ($bovine) {
                return $this->toMapSingle($bovine);
            });
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    private function toMapSingle($bovine)
    {
        try {
            $motherRgd = $bovine->mother ? $bovine->mother->rgd : null;
            return [
                'id' => $bovine->id,
                'serie' => $bovine->serie,
                'rgd' => $bovine->rgd,
                'sex' => $bovine->sex,
                'weight' => $bovine->weight,
                'birthdate' => $bovine->birthdate,
                'mother_rgd' => $motherRgd
            ];
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function create($request)
    {
        try {
            DB::beginTransaction();

            $serie = $request->input('serie');
            $rgd = $request->input('rgd');
            $propertyId = $request->input('property_id');

            $this->validarProperty($propertyId);

            $searchBovineWithSerie = $this->bovineRepository->existSerie($serie, $propertyId);
            $searchBovineWithRgd = $this->bovineRepository->existRgd($rgd, $propertyId);


            if ($searchBovineWithSerie || $searchBovineWithRgd) {

                if ($searchBovineWithSerie) {
                    return ['error' => 'Serie duplicated'];
                } else {
                    return ['error' => 'Rgd duplicated'];
                }
            }

            $this->bovineRepository->create($request);

            $bovines = $this->getBovines($propertyId);

            return [
                'message' => 'Bovine created successfully',
                'bovine' => $this->toMap($bovines)
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to create bovine', 'details' => $e->getMessage()];
        }
    }

    public function existSerieOrRgd($request)
    {
        try {
            $propertyId = $request->input('property_id');
            $this->validarProperty($propertyId);

            $typeIdentity = $request->input('type_identity');
            $valor = $request->input('valor');
            $exist = false;

            if ($typeIdentity == 'serie') {

                $bovine = $this->bovineRepository->existSerie($valor, $propertyId);
                if ($bovine) {
                    $exist = true;
                }
            } else {

                $bovine = $this->bovineRepository->existRgd($valor, $propertyId);
                if ($bovine) {
                    $exist = true;
                }
            }

            return [
                'message' => 'Bovine find successfully',
                'exist' => $exist,
                'bovine' => $this->toMapSingle($bovine)
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to search bovine', 'details' => $e->getMessage()];
        }
    }

    public function delete($request)
    {
        try {
            //code...
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    public function update()
    {
        try {
            //code...
        } catch (\Exception $e) {
            //throw $th;
        }
    }
}
