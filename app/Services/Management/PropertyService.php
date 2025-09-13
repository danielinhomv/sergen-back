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
       return $this->propertyRepository->getAllProperties($user_id);
    }

    public function createProperty($request)
    {
       return $this->propertyRepository->createProperty($request);
    }

    public function nameExists($request)
    {
        return $this->propertyRepository->nameExists($request);
    }

    public function updateProperty($id, $request)
    {
        return $this->propertyRepository->updateProperty($id, $request);
    }

    public function deleteProperty($id)
    {
        return $this->propertyRepository->deleteProperty($id);
    }

    public function getPropertyById($id)
    {
        return $this->propertyRepository->getPropertyById($id);
    }
}
