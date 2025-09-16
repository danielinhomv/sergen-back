<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Management\PresincronizationService;
use Illuminate\Http\Request;

class PresincronizationController extends Controller
{
    private PresincronizationService $presincronizationService;

    public function __construct(PresincronizationService $presincronizationService)
    {
        $this->presincronizationService = $presincronizationService;
    }

    public function create(Request $request)
    {
        $createResponse = $this->presincronizationService->create($request);

        if (isset($createResponse['error'])) {
            return response()->json(['error' => $createResponse['error']], 400);
        }

        return response()->json($createResponse);
    }

    public function get(Request $request)
    {
        $getResponse = $this->presincronizationService->get($request);

        if (isset($getResponse['error'])) {
            return response()->json(['error' => $getResponse['error']], 400);
        }

        return response()->json($getResponse);
    }
    
}
