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

    //function to create control for a property
    public function createControl(Request $request)
    {
        $propertyId = $request->input('property_id');
        $endDate = $request->input('end_date');
        $startDate = $request->input('start_date');

        $control = $this->controlService->createProtocolo($propertyId ,  $startDate, $endDate);
        if (isset($control['error'])) {
            return response()->json(['error' => $control['error']], 400);       
        }
        return response()->json($control);   
    }

    //function to update control for a property
    public function updateControl(Request $request)
    {
        $control = $this->controlService->updateControl($request);
        if (isset($control['error'])) {
            return response()->json(['error' => $control['error']], 400);       
        }
        return response()->json($control);   
    }

    //function to get last control by property id
    public function getLastControl(Request $request)
    {
        $propertyId = $request->input('property_id');
        $control = $this->controlService->getLastControl($propertyId);
        if (isset($control['error'])) {
            return response()->json(['error' => $control['error']], 400);       
        }
        return response()->json($control);   
    }

    //function to delete control by id 
    public function deleteControl(Request $request)
    {
        $controlId = $request->input('id');
        $control = $this->controlService->deleteControl($controlId);
        if (isset($control['error'])) {
            return response()->json(['error' => $control['error']], 400);       
        }
        return response()->json($control);   
    }

}