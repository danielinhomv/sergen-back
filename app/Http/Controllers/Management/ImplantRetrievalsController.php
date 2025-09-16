<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Management\ImplantRetrievalsService;
use Illuminate\Http\Request;

class ImplantRetrievalsController extends Controller
{
    private ImplantRetrievalsService $implantRetrievalsService;   

    public function __construct(ImplantRetrievalsService $implantRetrievalsService)
    {
        $this->implantRetrievalsService = $implantRetrievalsService;
    }   

    public function create(Request $request)
    {
        $createResponse = $this->implantRetrievalsService->create($request);

        if (isset($createResponse['error'])) {
            return response()->json(['error' => $createResponse['error']], 400);
        }

        return response()->json($createResponse);
    }

    public function get(Request $request)
    {
        $getResponse = $this->implantRetrievalsService->get($request);

        if (isset($getResponse['error'])) {
            return response()->json(['error' => $getResponse['error']], 400);
        }

        return response()->json($getResponse);
    }
    
}
    