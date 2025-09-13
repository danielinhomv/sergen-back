<?php

namespace App\Repositories\Management;

use App\Models\Property;

class PropertyRepository
{

    public function getAllProperties($user_id)
    {
        $properties = Property::where('user_id', $user_id)->get();
        return 
            [
                'message' => 'Properties retrieved successfully',
                'properties' => $this->toMap($properties)
            ];
        
    }

    private function toMap($properties)
    {
        return $properties->map(function ($property) {
            return [
                'id' => $property->id,
                'name' => $property->name,
                'place' => $property->place,
                'phone_number' => $property->phone_number,
                'owner_name' => $property->owner_name
            ];
        });
    }

    public function nameExists($request)
    {
        try {
            $name = $request->input('name');
            return Property::where('name', $name)->exists();
        } catch (\Exception $e) {
            return ['error' => 'Failed to check name existence', 'details' => $e->getMessage()];
        }

    }

    public function createProperty($request)
    {
        try {
            $propertie = Property::create($request);
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
            $property = Property::find($id);
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
            $property = Property::find($id);
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
            $property = Property::find($id);
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
