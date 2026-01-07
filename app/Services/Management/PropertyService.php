<?php

namespace App\Services\Management;

use App\Repositories\Management\ControlRepository;
use App\Repositories\Management\PropertyRepository;
use Exception;

class PropertyService
{

    private PropertyRepository $propertyRepository;

    public function __construct(
        PropertyRepository $propertyRepository
    ) {
        $this->propertyRepository = $propertyRepository;
    }

    public function getAllProperties($user_id)
    {
        try {
            $properties = $this->propertyRepository->findByUserId($user_id);

            if (!$properties) {
                return ['error' => 'properties Not Found'];
            }
            return
                [
                    'message' => 'Properties retrieved successfully',
                    'properties' => $this->toMap($properties)
                ];
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    private function toMap($properties)
    {
        try {
            return $properties->map(function ($property) {
                return $this->toMapSingle($property);
            });
        } catch (\Exception $e) {
            throw new Exception("error to map properties");
        }
    }

    public function createProperty($request)
    {
        try {

            $property = $this->propertyRepository->create($request);
            if (!$property) {
                return ['error' => 'Property Not found'];
            }
            return
                [
                    'message' => 'Property created successfully',
                    'property' => $this->toMapSingle($property)
                ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to create property', 'details' => $e->getMessage()];
        }
    }

    private function toMapSingle($property)
    {

        return [
            'id' => $property->id,
            'name' => $property->name,
            'place' => $property->place,
            'phone_number' => $property->phone_number,
            'owner_name' => $property->owner_name,
        ];
    }

    public function getPropertyById($id)
    {
        try {
            $property = $this->propertyRepository->findById($id);
            if (!$property) {
                return ['error' => 'Property not found'];
            }
            return
                [
                    'message' => 'Property retrieved successfully',
                    'property' => $this->toMapSingle($property)
                ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to retrieve property', 'details' => $e->getMessage()];
        }
    }

    public function updateProperty($id, $request)
    {
        try {
            $property = $this->propertyRepository->findById($id);

            if (!$property) {
                return ['error' => 'Property not found'];
            }

            $property->update($request->all());

            return
                [
                    'message' => 'Property updated successfully',
                    'property' => $this->toMapSingle($property)
                ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to update property', 'details' => $e->getMessage()];
        }
    }

    public function deleteProperty($id)
    {
        try {
            $property = $this->propertyRepository->findById($id);

            if (!$property) {
                return ['error' => 'Property not found'];
            }
            $property->delete();
            return
                [
                    'message' => 'Property deleted successfully',
                    'property' => $this->toMapSingle($property)
                ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to delete property', 'details' => $e->getMessage()];
        }
    }

    public function getControlsByPropertyId($property_id)
    {
        try {
            $property = $this->propertyRepository->findById($property_id);

            if (!$property) {
                return ['error' => 'Property not found'];
            }

            $controls = $property->controls;

            return [
                'message' => 'Controls retrieved successfully',
                'controls' => $controls
            ];  

        } catch (\Exception $e) {
            return ['error' => 'Failed to retrieve control', 'details' => $e->getMessage()];
        }
    }
}
