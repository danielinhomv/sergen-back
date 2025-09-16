<?php

namespace App\Services\Management;

use App\Repositories\Management\PropertyRepository;

class PropertyService
{

    private PropertyRepository $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository)
    {
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
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function nameExists($request)
    {
        try {
            $name = $request->input('name');
            $user_id = $request->input('user_id');

            $exists = $this->propertyRepository->exists($name, $user_id);

            return ['exists' => $exists];
        } catch (\Exception $e) {
            return ['error' => 'Failed to check name existence', 'details' => $e->getMessage()];
        }
    }

    public function createProperty($request)
    {
        try {
            $propertie = $this->propertyRepository->create($request);
            
            $propertie->save();
            return
                [
                    'message' => 'Property created successfully',
                    'property' => $this->toMapSingle($propertie)
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
                'owner_name' => $property->owner_name
            ];
      
    }

    public function getPropertyById($id)
    {
        try {
            $property = $this->propertyRepository->find($id);
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

    public function updateProperty($request, $id)
    {
        try {
            $property = $this->propertyRepository->find($id);

            if (!$property) {
                return ['error' => 'Property not found'];
            }
            $property->update($request);
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
            $property = $this->propertyRepository->find($id);

            if (!$property) {
                return ['error' => 'Property not found'];
            }
            $property->delete();
            return ['message' => 'Property deleted successfully'];
        } catch (\Exception $e) {
            return ['error' => 'Failed to delete property', 'details' => $e->getMessage()];
        }
    }
}
