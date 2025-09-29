<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\Management\InseminationServices;
use Illuminate\Http\Request;

class InseminationController extends Controller
{

    private InseminationServices $inseminationService;
    
    public function __construct(InseminationServices $inseminationService)
    {
        $this->inseminationService = $inseminationService;
        
    }

    public function create(Request $request){
        return $this->inseminationService->create($request);
    }

    public function all(Request $request){
        return $this->inseminationService->all($request);
    }

    public function delete(Request $request ){

    }

    public function update(Request $request){

    }
}