<?php

namespace App\Repositories\Management;

use App\Models\Bull;
use App\Models\User;

class BullRepository
{
    public function create($request)
    {
        try {
            $user_id = $request->input('user_id');
            $user = User::find($user_id);

            if (!$user) {
                return ['error' => 'User not found'];
            }

            $name = $request->input('name');

            $bull = Bull::create([
                'name' => $name,
                'user_id' => $user_id
            ]);

            $bull->save();

            return $this->toMapSingle($bull);
        } catch (\Exception $e) {
            return ['error' => 'Failed to create bull: ' . $e->getMessage()];
        }
    }

    public function exists($name, $user_id)
    {
        $exist = Bull::where('name', $name)
            ->where('user_id', $user_id)
            ->exists();

        return ['exists' => $exist];
    }

    public function all($user_id)
    {
        try {
            $user = User::find($user_id);
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
        return $bulls->map(function ($bull) {
            return [
                'id' => $bull->id,
                'name' => $bull->name,
            ];
        });
    }

    private function toMapSingle($bull)
    {
        return [
            'id' => $bull->id,
            'name' => $bull->name,
        ];
    }
}
