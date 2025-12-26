<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Management\BirthService;
use Illuminate\Http\Request;

class BirthController extends Controller
{
    private BirthService $birthService;

    public function __construct(BirthService $birthService)
    {
        $this->birthService = $birthService;
        
    }

    public function create(Request $request)
    {
        $birthCreate = $this->birthService->create($request);
        
        if(asset($birthCreate['error'])){
            return response()->json(
                [
                    'error' => $birthCreate['error']
                ],400
            );
        }

        return response()->json($birthCreate);
    }

    public function update(Request $request)
    {
        $birthUpdate = $this->birthService->update($request);
        
        if(asset($birthUpdate['error'])){
            return response()->json(
                [
                    'error' => $birthUpdate['error']
                ],400
            );
        }

        return response()->json($birthUpdate);
    }

    public function get (Request $request)
    {
        $birth = $this->birthService->get($request);
        
        if(asset($birth['error'])){
            return response()->json(
                [
                    'error' => $birth['error']
                ],400
            );
        }

        return response()->json($birth);
    }
    
}
