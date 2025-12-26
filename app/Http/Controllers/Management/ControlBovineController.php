<?php

namespace App\Http\Controllers\Management;
use App\Http\Controllers\Controller;
use App\Services\Management\ControlBovineService;
use Illuminate\Http\Request;    

class ControlBovineController extends Controller
{
    private ControlBovineService $controlBovineService;

    public function __construct(ControlBovineService $controlBovineService)
    {
        $this->controlBovineService = $controlBovineService;
    }

    public function create(Request $request)
    {
        $controlBovineCreate = $this->controlBovineService->createControlBovine($request);

        if(isset($controlBovineCreate['error'])){
            return response()->json(
                [
                'error' => $controlBovineCreate['error']
                ],400
                ); 
        }

        return response()->json($controlBovineCreate);
    }
}