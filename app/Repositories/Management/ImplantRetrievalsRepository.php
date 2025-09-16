<?php
namespace App\Repositories\Management;

use App\Models\Control_bovine;
use App\Models\Implant_retrieval;

class ImplantRetrievalsRepository 
{

    public function create($request)
    {
        try {

            $implantRetrieval = Implant_retrieval::create($request->all());
        
            if (!$implantRetrieval) {
                return ['error' => 'Failed to create Implant Retrieval'];
            }
        
           return $this->toMapSingle($implantRetrieval);
        
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    public function get($request)
    {
        try {
            $bovineControl=Control_bovine::where('bovine-controls_id', $request->input('bovine-controls_id'))->get();
            
            if (!$bovineControl) {
                return ['error' => 'Bovine Control not found'];
            }

            $implantRetrieval = $bovineControl->implant_retrieval;
            
            if (!$implantRetrieval) {
                return ['error' => 'Implant Retrieval not found'];
            }

            return $this->toMapSingle($implantRetrieval);
        
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

    private function  toMapSingle($implantRetrieval)
    {
        return [
            'id' => $implantRetrieval->id,
            'status' => $implantRetrieval->status,
            'work_team' => $implantRetrieval->work_team,
            'used_product_summary' => $implantRetrieval->used_product_summary,
            'date' => $implantRetrieval->date
        ];
    }
    // --- IGNORE ---
}