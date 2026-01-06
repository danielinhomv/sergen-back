<?php

namespace App\Services\Management;

use App\Repositories\Management\ControlRepository;
use App\Repositories\Management\PropertyRepository;

class ControlService
{
    private PropertyRepository $propertyRepository;
    private ControlRepository $controlRepository;

    public function __construct(PropertyRepository $propertyRepository, ControlRepository $controlRepository)
    {
        $this->propertyRepository = $propertyRepository;
        $this->controlRepository = $controlRepository;
    }

    public function createProtocolo($propertyId, $startDate, $endDate)
    {

        try {
            $property = $this->propertyRepository->findById($propertyId);
            if (!$property) {
                return ['error' => 'Property not found'];
            }
            $control = $this->controlRepository->create($propertyId, $startDate, $endDate);
            return [
                'message' => 'Control created successfully',
                'control' => $control
            ];
        } catch (\Exception $e) {
            return ['error' => 'Property not found'];
        }
    }

    // delete control by id
    public function deleteControl($id)
    {
        try {
            $control = $this->controlRepository->findById($id);
            if (!$control) {
                return ['error' => 'Control not found'];

            }
            $control->delete();
            return [
                'message' => 'Control deleted successfully',
                'control' => $control
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to delete control', 'details' => $e->getMessage()];
        }
    }
    
    // actualizar control por id
    public function updateControl($request)
    {
        try {
            $control = $this->controlRepository->findById($request->input('id'));
            if (!$control) {
                return ['error' => 'Control not found'];
            }
            $control->update($request->all());
            return [
                'message' => 'Control updated successfully',
                'control' => $control
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to update control', 'details' => $e->getMessage()];
        }
    }

    // get last control by property id
    public function getLastControl($propertyId)
    {
        try {
            $controls = $this->controlRepository->findByPropertyId($propertyId);
            //mapear todos los controles y devolver todo aunque sea vacio
            return [
                'message' => 'Controls retrieved successfully',
                'controls' => $this->toMapMultiple($controls)
            ];

        } catch (\Exception $e) {
            return ['error' => 'Failed to retrieve control', 'details' => $e->getMessage()];
        }
    }

    //map single control
    private function toMapSingle($control)
    {
        return [
            'id' => $control->id,
            'property_id' => $control->property_id,
            'start_date' => $control->start_date,
            'end_date' => $control->end_date 
        ]; 
    }

    //map multiple controls
    private function toMapMultiple($controls)
    {
        $mappedControls = [];
        foreach ($controls as $control) {
            $mappedControls[] = $this->toMapSingle($control);
        }
        return $mappedControls;
    }       
}