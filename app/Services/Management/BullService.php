<?php

namespace App\Services\Management;

use App\Repositories\Management\BullRepository;
use App\Repositories\Management\UserRepository;

class BullService
{
    private UserRepository $userRepository;
    private BullRepository $bullRepository;

    public function __construct(UserRepository $userRepository, BullRepository $bullRepository)
    {
        $this->userRepository = $userRepository;
        $this->bullRepository = $bullRepository;
    }
    
    public function create($request)
    {
        try {
            $user_id = $request->input('user_id');
            $user = $this->userRepository->find($user_id);

            if (!$user) {
                return ['error' => 'User not found'];
            }
            $name = $request->input('name');
            $exists = $this->bullRepository->exits($name, $user_id);
            if ($exists) {
                return ['error' => 'Bull with the same name already exists for this user'];
            }
            $bull = $this->bullRepository->create($request);

            $bull->save();

             return [
                'message' => 'Bull created successfully',
                'bull' => $this->toMapSingle($bull)
            ];

        } catch (\Exception $e) {
            return ['error' => 'Failed to create bull: ' . $e->getMessage()];
        }
    }

    public function exists($request)
    {
        $name = $request->input('name');
        $user_id = $request->input('user_id');  

        $exist = $this->bullRepository->exits($name, $user_id);
        
        return ['exists' => $exist];
    }

    public function all($request)
    {
        try {
            $user_id = $request->input('user_id');
            $user = $this->userRepository->find($user_id);
            
            if (!$user) {
                return ['error' => 'User not found'];
            }
            
            $bulls = $user->bulls;
            
             return [
                'message' => 'Bull Retrievals successfully',
                'bovine' =>$this->toMap($bulls)
            ];

        } catch (\Exception $e) {
            return ['error' => 'Failed to retrieve bulls: ' . $e->getMessage()];
        }
    }

    private function toMap($bulls)
    {
        try {
            return $bulls->map(function ($bull) {
                return [
                    'id' => $bull->id,
                    'name' => $bull->name,
                ];
            });
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    private function toMapSingle($bull)
    {
        try {
            return [
                'id' => $bull->id,
                'name' => $bull->name,
            ];
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    //update con request
    public function update($request)
    {
        try {
            $user_id = $request->input('user_id');
            $user = $this->userRepository->find($user_id);

            if (!$user) {
                return ['error' => 'User not found'];
            }

            $bull_id = $request->input('bull_id');
            $bull = $this->bullRepository->find($bull_id, $user_id);
            if (!$bull) {
                return ['error' => 'Bull not found'];
            }
            $bull->name = $request->input('name', $bull->name);
            $bull->save();
            return [
                'message' => 'Bull updated successfully',
                'bull' => $this->toMapSingle($bull)
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to update bull: ' . $e->getMessage()];
        }
    }
    
    //eliminar bull
    public function delete($request)
    {
        try {
            $bull_id = $request->input('id');
            $bull = $this->bullRepository->findById($bull_id);
            if (!$bull) {
                return ['error' => 'Bull not found'];
            }
            $bull->delete();
            return [
                'message' => 'Bull deleted successfully',
                'bull' => $this->toMapSingle($bull)
            ];
        } catch (\Exception $e) {
            return ['error' => 'Failed to delete bull: ' . $e->getMessage()];
        }
    }
}