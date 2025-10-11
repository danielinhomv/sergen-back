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
            return response()->json([
                'error' => $createResponse['error'],
            ], 400);
        }

        return response()->json($createResponse);
    }

    public function all(Request $request)
    {
        $property_id = $request->input('property_id');
        $bovines = $this->bovineService->all($property_id);
        if (isset($bovines['error'])) {
            return response()->json(['error' => $bovines['error']], 400);
        }
        return response()->json($bovines);
    }

    public function update(Request $request)
    {
        $bovineUpdated = $this->bovineService->update($request);
        if (isset($bovineUpdated['error'])) {
            return response()->json([
                'error' => $bovineUpdated['error'],
            ], 400);
        }
        return response()->json($bovineUpdated);
    }

    public function delete(Request $request)
    {
        $bovineDeleted = $this->bovineService->delete($request);
        if (isset($bovineDeleted['error'])) {
            return response()->json(['error' => $bovineDeleted['error']], 400);
        }
        return response()->json($bovineDeleted);
    }

    public function existRgdOrSerie(Request $request)
    {
        $exist = $this->bovineService->existSerieOrRgd($request);
        if (isset($exist['error'])) {
            return response()->json(['error' => $exist['error']], 400);
        }
        return response()->json($exist);
    }
}
