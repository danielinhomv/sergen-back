<?php

namespace App\Services\Management;

use App\Repositories\Management\BullRepository;

class BullService
{
    private BullRepository $bullRepository;

    public function __construct(BullRepository $bullRepository)
    {
        $this->bullRepository = $bullRepository;
    }

    public function create($request)
    {
        return $this->bullRepository->create($request);
    }

    public function exists($request)
    {
        $name = $request->input('name');
        $user_id = $request->input('user_id');
        return $this->bullRepository->exists($name, $user_id);
    }

    public function all($request)
    {
        $user_id = $request->input('user_id');
        return $this->bullRepository->all($user_id);
    }

    // Aquí puedes agregar métodos específicos para la lógica de negocio relacionada con los toros (Bulls)
}
