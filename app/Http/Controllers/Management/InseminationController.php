<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Management\InseminationService;
use Illuminate\Http\Request;

class InseminationController extends Controller
{

    private InseminationService $inseminationService;

    public function __construct(InseminationService $inseminationService)
    {
        $this->inseminationService = $inseminationService;
    }

    public function create(Request $request)
    {
        $inseminationCreated = $this->inseminationService->create($request);

        if (isset($inseminationCreated['error'])) {
            return response()->json(['error' => $inseminationCreated['error']], 400);
        }

        return response()->json($inseminationCreated);
    }

    public function all(Request $request)
    {
        $inseminations =  $this->inseminationService->all($request);

        if (isset($inseminations['error'])) {
            return response()->json(['error' => $inseminations['error']], 400);
        }
        return response()->json($inseminations);
    }

    public function delete(Request $request)
    {
        $inseminationDeleted = $this->inseminationService->delete($request);

        if (isset($inseminationDeleted['error'])) {
            return response()->json(['error' => $inseminationDeleted['error']], 400);
        }

        return response()->json($inseminationDeleted);
    }

    public function update(Request $request)
    {
        $inseminationUpdated = $this->inseminationService->update($request);

        if (isset($inseminationUpdated['error'])) {
            return response()->json(['error' => $inseminationUpdated['error']], 400);
        }

        return response()->json($inseminationUpdated);
    }
}
