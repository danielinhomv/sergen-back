<?php

namespace App\Repositories\Management;

use App\Models\Implant_retrieval;

class ImplantRetrievalsRepository
{

    public function create($request)
    {
        return Implant_retrieval::create($request->all());
    }

    public function update($request)
    {
        $implantRetrieval = Implant_retrieval::find($request->input('id'));
        if ($implantRetrieval) {
            $implantRetrieval->update($request->all());
            return $implantRetrieval;
        }
        return null;
    }

}