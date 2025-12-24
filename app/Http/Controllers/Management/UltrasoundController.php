<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Management\UltrasoundService;
use Illuminate\Http\Request;

class UltrasoundController extends Controller
{
    private UltrasoundService $ultrasoundService;   

    public function __construct(UltrasoundService $ultrasoundService)
    {
        $this->ultrasoundService = $ultrasoundService;
    }   

    public function create(Request $request)
    {
        $createResponse = $this->ultrasoundService->create($request);

        if (isset($createResponse['error'])) {
            return response()->json(['error' => $createResponse['error']], 400);
        }

        return response()->json($createResponse);
    }

    public function get(Request $request)
    {
        $getResponse = $this->ultrasoundService->get($request);

        if (isset($getResponse['error'])) {
            return response()->json(['error' => $getResponse['error']], 400);
        }

        return response()->json($getResponse);
    }

    public function update(Request $request)
    {
        $updateResponse = $this->ultrasoundService->update($request);

        if (isset($updateResponse['error'])) {
            return response()->json(['error' => $updateResponse['error']], 400);
        }

        return response()->json($updateResponse);
    }
    
}
