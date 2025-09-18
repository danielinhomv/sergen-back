<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Management\GeneralPalpationService;
use Illuminate\Http\Request;

class GeneralPalpationController extends Controller
{
    private GeneralPalpationService $generalPalpationService;

    public function __construct(GeneralPalpationService $generalPalpationService)
    {
        $this->generalPalpationService = $generalPalpationService;
    }

    public function create(Request $request)
    {
        $generalPalpationCreate = $this->generalPalpationService->create($request);

        if(isset($generalPalpationCreate['error'])){
            return response()->json(
                [
                'error' => $generalPalpationCreate['error']
                ],400
                ); 
        }

        return response()->json($generalPalpationCreate);
    }

    public function get(Request $request)
    {
        $generalPalpation = $this->generalPalpationService->get($request);

        if(isset($generalPalpation['error'])){
            return response()->json(
                [
                'error' => $generalPalpation['error']
                ],400
                ); 
        }

        return response()->json($generalPalpation);

    }
}
