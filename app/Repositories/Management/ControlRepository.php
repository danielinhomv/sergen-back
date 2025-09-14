<?php

namespace App\Repositories\Management;

use App\Models\Control;
use App\Models\Property;
use Illuminate\Support\Facades\DB;

class ControlRepository
{
    private function createProtocolo($property_id)
    {

            $protocolo = Control::create([
                'status' => 'in progress',
                'property_id' => $property_id,
            ]);
            $protocolo->save();
        
    }

    public function startNewProtocol($property_id)
    {
        try {
            DB::beginTransaction();

            $property = Property::find($property_id);

            if (!$property) {
                DB::rollBack();
                return ['error' => 'Property not found'];
            }

            $control = $property->control;
            
            if ($control) {
                $control->delete();
            }

            $protocolo=$this->createProtocolo($property_id);

            DB::commit();

            return [
                'message' => 'Protocolo updated successfully',
                'protocolo' => $protocolo
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => 'Failed to start new protocol', 'details' => $e->getMessage()];
        }
    }
    
}
