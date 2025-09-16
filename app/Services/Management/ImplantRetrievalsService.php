<?php
namespace App\Repositories\Management;

class ImplantRetrievalsService 
{

    private ImplantRetrievalsRepository $implantRetrievalsRepository;   

    public function __construct(ImplantRetrievalsRepository $implantRetrievalsRepository)
    {
        $this->implantRetrievalsRepository = $implantRetrievalsRepository;
    }

    public function create($request)
    {
        return $this->implantRetrievalsRepository->create($request);
    }

    public function get($request)
    {
        return $this->implantRetrievalsRepository->get($request);
    }

    
}