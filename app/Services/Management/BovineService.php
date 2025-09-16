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

    public function all($property_id)
    {
        $property = $this->propertyRepository->find($property_id);
        if (!$property) {
            return ['error' => 'Property not found'];
        }
        $bovines = $property->bovines;
        return $this->toMap($bovines);
    }

    private function toMap($bovines)
    {
        try {
            return $bovines->map(function ($bovine) {
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
            });          //code...
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
            $bovine = $this->bovineRepository->findBySerie($serie);

            if ($bovine) {
                $bovine->delete();
            }

            $bovine = $this->bovineRepository->create($request);

            DB::commit();

            return [
                'message' => 'Bovine created successfully',
                'bovine' => $this->toMapSingle($bovine)
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => 'Failed to create bovine', 'details' => $e->getMessage()];
        }
    }

}
