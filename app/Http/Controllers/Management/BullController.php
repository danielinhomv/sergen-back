<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Management\BullService;
use Illuminate\Http\Request;

class BullController extends Controller
{
    private BullService $bullService;   

    public function __construct(BullService $bullService)
    {
        $this->bullService = $bullService;
    }   

    public function create(Request $request)
    {
        $createResponse = $this->bullService->create($request);

        if (isset($createResponse['error'])) {
            return response()->json(['error' => $createResponse['error']], 400);
        }

        return response()->json($createResponse);
    }

    public function all(Request $request)
    {
        $bulls = $this->bullService->all($request);
        if (isset($bulls['error'])) {
            return response()->json(['error' => $bulls['error']], 400);
        }
        return response()->json($bulls);
    }

    public function exists(Request $request)
    {
        $existsResponse = $this->bullService->exists($request);
        if (isset($existsResponse['error'])) {
            return response()->json(['error' => $existsResponse['error']], 400);
        }
        return response()->json($existsResponse);
    }


    public function update(Request $request)
    {
        $updateResponse = $this->bullService->update($request);
        if (isset($updateResponse['error'])) {
            return response()->json(['error' => $updateResponse['error']], 400);
        }
        return response()->json($updateResponse);
    }

    public function delete(Request $request)
    {
        $deleteResponse = $this->bullService->delete($request);
        if (isset($deleteResponse['error'])) {
            return response()->json(['error' => $deleteResponse['error']], 400);
        }
        return response()->json($deleteResponse);
    }
}