<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Management\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    private PropertyService $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    public function listProperties($request)
    {
        $user_id = $request->input('user_id');
        $properties = $this->propertyService->getAllProperties($user_id);
        if (isset($properties['error'])) {
            return response()->json(['error' => $properties['error']], 400);
        }
        return response()->json($properties);
    }

    public function createProperty(Request $request)
    {
        $property = $this->propertyService->createProperty($request->all());
        if (isset($property['error'])) {
            return response()->json(['error' => $property['error']], 400);
        }
        return response()->json($property);
    }

    public function updateProperty($id, Request $request)
    {
        $property = $this->propertyService->updateProperty($id, $request);
        if (isset($property['error'])) {
            return response()->json(['error' => $property['error']], 400);
        }
        return response()->json($property);
    }

    public function deleteProperty($id)
    {
        $property = $this->propertyService->deleteProperty($id);
        if (isset($property['error'])) {
            return response()->json(['error' => $property['error']], 400);
        }
        return response()->json($property);
    }

    public function nameExists(Request $request)
    {
        $name = $request->input('name');
        $exist = $this->propertyService->nameExists($name);
        if (isset($exist['error'])) {
            return response()->json(['error' => $exist['error']], 400);
        }
        return response()->json($exist);
    }

    public function getPropertyById($id)
    {
        $property = $this->propertyService->getPropertyById($id);
        if (isset($property['error'])) {
            return response()->json(['error' => $property['error']], 400);
        }
        return response()->json($property);
    }
}
