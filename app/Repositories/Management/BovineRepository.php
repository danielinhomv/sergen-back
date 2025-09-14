<?php

namespace App\Repositories\Management;

use App\Models\Bovine;
use App\Models\Property;
use Illuminate\Support\Facades\DB;

class BovineRepository
{

    public function all($property_id)
    {
        $property = Property::find($property_id);
        if (!$property) {
            return ['error' => 'Property not found'];
        }
        $bovines = $property->bovines;
        return $this->toMap($bovines);
    }

    private function toMap($bovines)
    {

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
        });
    }

    private function toMapSingle($bovine)
    {
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
    }

    public function create($request)
    {
        try {
            DB::beginTransaction();

            $serie = $request->input('serie');
            $bovine = Bovine::find($serie);
            
            if ($bovine) {
                $bovine->delete();
            }
            
            $bovine = $this->createBovine($request);
            
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

    private function createBovine($request)
    {
        $bovine = Bovine::create($request->all());
        $bovine->save();
        return $bovine;
    }
}
