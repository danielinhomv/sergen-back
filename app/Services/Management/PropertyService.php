<?php

namespace App\Services\Management;

use App\Repositories\Management\PropertyRepository;
use App\Repositories\Management\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class PropertyService
{

    private PropertyRepository $propertyRepository;
    private UserRepository $userRepository;
    private ControlService $controlService;

    public function __construct(
        PropertyRepository $propertyRepository,
        UserRepository $userRepository,
        ControlService $controlService
    ) {
        $this->propertyRepository = $propertyRepository;
        $this->userRepository = $userRepository;
        $this->controlService = $controlService;
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
            DB::beginTransaction();

            $property = $this->propertyRepository->create($request);
            if (!$property) {
                return ['error' => 'Property Not found'];
            }

            $protocolo = $this->controlService->createProtocolo($property->id);
            if (!$protocolo) {
                DB::rollBack();
                return ['error' => 'Failed to create protocol'];
            }

            DB::commit();

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

            $currentSession = $user->currentSession;

            $property_id = $request->input('property_id');
            $property = $this->propertyRepository->findById($property_id);
            if (!$property) {
                return ['error' => 'Property not found'];
            }
            $control = $property->control;

            $name = $property->name;
            $place = $property->place;
            $phone_number = $property->phone_number;
            $owner_name = $property->owner_name;

            $currentSession->update([
                'property_id' => $property_id,
                'name' => $name,
                'place' => $place,
                'phone_number' => $phone_number,    
                'owner_name' => $owner_name,    
                'active' => true
            ]);

            return [
                'message' => 'current_session start successfully',
                'current_session' => $currentSession,
                'protocol_id' => $control->id
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

            $currentSession = $user->currentSession;
            $currentSession->update(['active' => false]);

            return [
                'message' => 'current_session finalized successfully',
                'current_session' => $currentSession
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to finalized current_session', 'details' => $e->getMessage()];
        }
    }

    public function isWorked($request)
    {
        try {
            $user = $this->userRepository->find($request->input('user_id'));

            if (!$user) {
                return ['error' => 'User not found'];
            }

            $currentSession = $user->currentSession;

            $property = $this->propertyRepository->findById($currentSession->property_id);
            if (!$property) {
                return ['error' => 'Property not found'];
            }

            $name = $property->name;
            $place = $property->place;
            $phone_number = $property->phone_number;
            $owner_name = $property->owner_name;

            $control = $property->control;

            if ($currentSession->isActive()) {
                return [
                    "property_id" => $currentSession->property_id,
                    "name" => $name,
                    "place" => $place,
                    "phone_number" => $phone_number,
                    "owner_name" => $owner_name,
                    "active" => true,
                    "protocol_id" => $control->id
                ];
            }

            return ["active" => false];
        } catch (\Throwable $e) {
            return ['error' => 'Failed to verify', 'details' => $e->getMessage()];
        }
    }
}
