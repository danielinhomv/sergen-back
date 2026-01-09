<?php

namespace App\Services\Management;

use App\Repositories\Management\BovineRepository;
use App\Repositories\Management\PropertyRepository;
use Exception;

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
                'bovines' => $this->toMap($bovines)
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
        return $property;
    }

    private function toMap($bovines)
    {
            return $bovines->map(function ($bovine) {
                return $this->toMapSingle($bovine);
            });

    }

    private function toMapSingle($bovine)
    {
        try {
            $motherRgd = $bovine->bovineMother ? $bovine->bovineMother->rgd : null;
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
            throw new Exception("error to map bovine");
        }
    }

    public function create($request)
    {
        try {

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
                'bovines' => $this->toMap($bovines)
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to create bovine', 'details' => $e->getMessage()];
        }
    }

    public function delete($request)
    {
        try {
            $bovineId = $request->input('id');
            $propertyId = $request->input('property_id');
            $bovineFinded = $this->bovineRepository->findById($bovineId, $propertyId);

            $bovineFinded->delete();

            $bovines = $this->getBovines($propertyId);

            return [
                'message' => 'Bovine retrievals successfully',
                'bovines' => $this->toMap($bovines)
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to delete bovine', 'details' => $e->getMessage()];
        }
    }

    public function update($request)
    {
        try {
            $bovineId = $request->input('id');
            $propertyId = $request->input('property_id');
            $bovineFinded = $this->bovineRepository->findById($bovineId, $propertyId);

            $serie = $request->input('serie');
            $rgd = $request->input('rgd');

            if ($serie && $serie != $bovineFinded->serie) {
                if ($this->existSerieAt($serie, $propertyId)) {
                    return ['error' => 'serie duplicated'];
                }
            }

            if ($rgd && $rgd != $bovineFinded->rgd) {
                if ($this->existRgdAt($rgd, $propertyId)) {
                    return ['error' => 'rgd duplicated'];
                }
            }

            $bovineFinded->update($request->all());

            $bovines = $this->getBovines($propertyId);

            return [
                'message' => 'Bovine retrievals successfully',
                'bovines' => $this->toMap($bovines)
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to update bovine', 'details' => $e->getMessage()];
        }
    }

    public function getBySerie($request)
    {
        try {
            $serie = $request->input('serie');
            $propertyId = $request->input('property_id');

            $bovine = $this->bovineRepository->findBySerie($serie, $propertyId);

            if (!$bovine) {
                return ['error' => 'Bovine not found'];
            }

            return [
                'message' => 'Bovine retrieved successfully',
                'bovine' => $this->toMapSingle($bovine)
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to retrieve bovine', 'details' => $e->getMessage()];
        }
    }

    private function existSerieAt($valor, $propertyId)
    {
        $bovine = $this->bovineRepository->existSerie($valor, $propertyId);
        return $bovine != null;
    }

    private function existRgdAt($valor, $propertyId)
    {
        $bovine = $this->bovineRepository->existRgd($valor, $propertyId);
        return $bovine != null;
    }


}
