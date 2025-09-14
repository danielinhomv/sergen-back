<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Management\BovineService;
use Illuminate\Http\Request;

class BovineController extends Controller
{
    private BovineService $bovineService;

    public function __construct(BovineService $bovineService)
    {
        $this->bovineService = $bovineService;
    }

    public function create(Request $request)
    {
        $createResponse = $this->bovineService->create($request);

        if (isset($createResponse['error'])) {
            return response()->json(['error' => $createResponse['error']], 400);
        }

        return response()->json($createResponse);
    }

    public function all($request)
    {
        $property_id = $request->input('property_id');
        $bovines = $this->bovineService->all($property_id);
        if (isset($bovines['error'])) {
            return response()->json(['error' => $bovines['error']], 400);
        }
        return response()->json($bovines);
    }
}
