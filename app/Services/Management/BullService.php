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

            $bull = $this->bullRepository->create($name,$user_id);

            $bull->save();

            return $this->toMapSingle($bull);
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

    public function all($user_id)
    {
        try {
            $user = $this->userRepository->find($user_id);
            if (!$user) {
                return ['error' => 'User not found'];
            }
            $bulls = $user->bulls;
            return $this->toMap($bulls);
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
}
