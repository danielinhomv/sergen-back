<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Management\ConfirmatoryUltrasoundService;
use Illuminate\Http\Request;

class ConfirmatoryUltrasoundController extends Controller
{
    private ConfirmatoryUltrasoundService $confirmatoryUltrasoundService;

    public function __construct(ConfirmatoryUltrasoundService $confirmatoryUltrasoundService)
    {
        $this->confirmatoryUltrasoundService = $confirmatoryUltrasoundService;
    }

    public function create(Request $request){
        
        $confirmatoryUltrasoundCreate = $this->confirmatoryUltrasoundService->create($request);
        
        if(isset($confirmatoryUltrasoundCreate['error']))
        {
            return response()->json(
                [
                    'error'=>$confirmatoryUltrasoundCreate['error'] 
                ],400
            );
        }

        return response()->json($confirmatoryUltrasoundCreate);
    }

    public function all(Request $request){


        $confirmatoryUltrasounds = $this->confirmatoryUltrasoundService->all($request);
        
        if(isset($confirmatoryUltrasounds['error']))
        {
            return response()->json(
                [
                    'error'=>$confirmatoryUltrasounds['error'] 
                ],400
            );
        }
        
        return response()->json($confirmatoryUltrasounds);

    }

    public function update(Request $request)
    {
        $confirmatoryUltrasoundUpdate = $this->confirmatoryUltrasoundService->update($request);
        
        if(isset($confirmatoryUltrasoundUpdate['error']))
        {
            return response()->json(
                [
                    'error'=>$confirmatoryUltrasoundUpdate['error'] 
                ],400
            );
        }

        return response()->json($confirmatoryUltrasoundUpdate);
    }

    public function delete(Request $request)
    {
        $confirmatoryUltrasoundDelete = $this->confirmatoryUltrasoundService->delete($request->input('id'));

        if(isset($confirmatoryUltrasoundDelete['error']))
        {
            return response()->json(
                [
                    'error'=>$confirmatoryUltrasoundDelete['error'] 
                ],400
            );
        }

        return response()->json($confirmatoryUltrasoundDelete);
    }
}
