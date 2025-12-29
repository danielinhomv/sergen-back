<?php

namespace App\Services\Management;

use App\Repositories\Management\ControlRepository;
use App\Repositories\Management\PropertyRepository;
use Illuminate\Support\Facades\DB;

class ControlService
{
    private PropertyRepository $propertyRepository;
    private ControlRepository $controlRepository;

    public function __construct(PropertyRepository $propertyRepository, ControlRepository $controlRepository)
    {
        $this->propertyRepository = $propertyRepository;
        $this->controlRepository = $controlRepository;
    }

    public function createProtocolo($propertyId)
    {

        $protocolo = $this->controlRepository->create($propertyId);
        $protocolo->save();
        return $protocolo;
    }
    // se debe crear un nuevo protocolo pero no eliminar el anterior , se establece
    // la fecha del sistema a la fecha de fin del protocolo anterior y cambiar el estado a finalizado
    public function startNewProtocol($request)
    {
        try {
            DB::beginTransaction();

            $property_id = $request->input('property_id');
            $property = $this->propertyRepository->findById($property_id);

            if (!$property) {
                return ['error' => 'Property not found'];
            }

            $lastControl = $this->controlRepository->getLastControl($property_id);
            if ($lastControl) {
                $lastControl->end_date = now();
                $lastControl->status = 'finished';
                $lastControl->save();
            }


            $protocolo = $this->createProtocolo($property_id);
            if (!$protocolo) {
                DB::rollBack();
                return ['error' => 'Failed to create protocol'];
            }

            DB::commit();

            return [
                'message' => 'Protocolo created successfully',
                'protocolo' => $protocolo
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => 'Failed to start new protocol', 'details' => $e->getMessage()];
        }
    }
}
