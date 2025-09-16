<?php
namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Management\ControlService;
use Illuminate\Http\Request;

class ControlController extends Controller
{

    private ControlService $controlService;

    public function __construct(ControlService $controlService)
    {
        $this->controlService = $controlService;
    }

    public function startNewProtocol(Request $request)
    {
        $property_id = $request->input('property_id');
        $protocol = $this->controlService->startNewProtocol($property_id);
        if (isset($protocol['error'])) {
            return response()->json(['error' => $protocol['error']], 400);
        }
        return response()->json($protocol);
    }
}