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
}
