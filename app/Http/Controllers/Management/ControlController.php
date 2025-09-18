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
        $protocol = $this->controlService->startNewProtocol($request);
        if (isset($protocol['error'])) {
            return response()->json(['error' => $protocol['error']], 400);
        }
        return response()->json($protocol);
    }
}