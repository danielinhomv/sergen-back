<?php

namespace App\Services\Management;

use App\Repositories\Management\PropertyRepository;
use App\Repositories\Management\UserRepository;

class PropertyService
{

    private PropertyRepository $propertyRepository;
    private UserRepository $userRepository;
    public function __construct(PropertyRepository $propertyRepository, UserRepository $userRepository)
    {
        $this->propertyRepository = $propertyRepository;
        $this->userRepository = $userRepository;
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

    public function startWork($request)
    {
        try {
            //code...
            $user_id = $request->input('user_id');
            $user = $this->userRepository->find($user_id);

            if (!$user) {
                return [
                    'error' => 'User not found'
                ];
            }

            $currentSession = $user->current_session;
            $property_id = $request->input('property_id');

            if (!$currentSession) {
                $currentSession = $this->propertyRepository->createCurrentSession($user_id, $property_id);
            } else {
                $currentSession->update([
                    'property_id' => $property_id,
                    'active' => true
                ]);
            }

            return [
                'message' => 'current_session start successfully',
                'current_session' => $currentSession
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to start current_session', 'details' => $e->getMessage()];
        }
    }

    public function finishWork($request)
    {
        try {
            $user_id = $request->input('user_id');
            $user = $this->userRepository->find($user_id);

            if (!$user) {
                return [
                    'error' => 'User not found'
                ];
            }

            $currentSession = $user->current_session;
            $currentSession->update(['active' => false]);

            return [
                'message' => 'current_session finalized successfully',
                'current_session' => $currentSession
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to finalized current_session', 'details' => $e->getMessage()];
        }
    }
}
