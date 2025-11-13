<?php

class BovineReportService
{
    protected $bovineReportRepository;

    public function __construct(BovineReportRepository $bovineReportRepository)
    {
        $this->bovineReportRepository = $bovineReportRepository;
    }

    public function generateBovineHistoryReport($bovine_id, $property_id)
    { 
        try {       
            $respuesta = $this->bovineReportRepository->generateBovineHistoryReport($bovine_id, $property_id);
            if (isset($respuesta['error'])) {
                return $respuesta;
            }
            return $respuesta;
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }   
    }

    public function generatePropertyBovineHistoryReport($property_id)
    {
        try {       
            $respuesta = $this->bovineReportRepository->generatePropertyBovineHistoryReport($property_id);
            if (isset($respuesta['error'])) {
                return $respuesta;
            }
            return $respuesta;
        } catch (\Exception $e) {
            return ['error' => 'Exception occurred: ' . $e->getMessage()];
        }
    }

}