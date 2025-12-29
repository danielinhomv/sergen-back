<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Management\BirthController;
use App\Http\Controllers\Management\BovineController;
use App\Http\Controllers\Management\BullController;
use App\Http\Controllers\Management\ConfirmatoryUltrasoundController;
use App\Http\Controllers\Management\ControlController;
use App\Http\Controllers\Management\GeneralPalpationController;
use App\Http\Controllers\Management\ImplantRetrievalsController;
use App\Http\Controllers\Management\InseminationController;
use App\Http\Controllers\Management\PresincronizationController;
use App\Http\Controllers\Management\PropertyController;
use App\Http\Controllers\Management\UltrasoundController;
use App\Http\Controllers\Report\BovineReportController;
use App\Http\Controllers\Report\InseminationReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('management')->group(function () {

  Route::prefix('birth')->group(function () {

    Route::post('/create', [BirthController::class, 'create']);
    Route::post('/get', [BirthController::class, 'get']);
    Route::post('/update', [BirthController::class, 'update']);
  });

  Route::prefix('bovine')->group(function () {

    Route::post('/create', [BovineController::class, 'create']);
    Route::post('/all',  [BovineController::class, 'all']);
    Route::post('/update', [BovineController::class , 'update']);
    Route::post('/delete', [BovineController::class , 'delete']);
    Route::post('/get-by-serie', [BovineController::class , 'getBySerie']);
  });

  Route::prefix('bull')->group(function () {

    Route::post('/create', [BullController::class, 'create']);   
    Route::post('/all', [BullController::class, 'all']);        
    Route::post('/update', [BullController::class, 'update']); 
  });

  Route::prefix('confirmatory-ultrasound')->group(function () {

    Route::post('/create', [ConfirmatoryUltrasoundController::class, 'create']);
    Route::post('/all', [ConfirmatoryUltrasoundController::class, 'all']);
    Route::post('/update', [ConfirmatoryUltrasoundController::class, 'update']);
    Route::post('/delete', [ConfirmatoryUltrasoundController::class, 'delete']);

  });

  Route::prefix('protocol')->group(function () {

    Route::post('/start', [ControlController::class, 'startNewProtocol']);
  });

  Route::prefix('general-palpation')->group(function () {

    Route::post('/create', [GeneralPalpationController::class, 'create']);
    Route::post('/get', [GeneralPalpationController::class, 'get']);
    Route::post('/update', [GeneralPalpationController::class, 'update']);
  });

  Route::prefix('implant-retrieval')->group(function () {

    Route::post('/create', [ImplantRetrievalsController::class, 'create']);
    Route::post('/get', [ImplantRetrievalsController::class, 'get']);
    Route::post('/update', [ImplantRetrievalsController::class, 'update']);
  });

  Route::prefix('insemination')->group(function () {

    Route::post('/create', [InseminationController::class, 'create']);
    Route::post('/all', [InseminationController::class, 'all']);
    Route::put('/update/{id}', [InseminationController::class, 'update']);
    Route::post('/delete', [InseminationController::class, 'delete']);
  });

  Route::prefix('pre-sincronizacion')->group(function () {

    Route::post('/create', [PresincronizationController::class, 'create']); 
    Route::post('/get', [PresincronizationController::class, 'get']);
    Route::post('/update', [PresincronizationController::class, 'update']);
  });

  Route::prefix('property')->group(function () {

    Route::get('/list', [PropertyController::class, 'listProperties']);
    Route::post('/create', [PropertyController::class, 'createProperty']);
    Route::put('/update/{id}', [PropertyController::class, 'updateProperty']);
    Route::delete('/delete/{id}', [PropertyController::class, 'deleteProperty']);

    Route::get('/{id}', [PropertyController::class, 'getPropertyById']);
    Route::post('/start-work', [PropertyController::class, 'startWork']);
    Route::post('/finish-work', [PropertyController::class, 'finishWork']);
    Route::post('/isWorked', [PropertyController::class, 'isWorked']);
    Route::get('/controls/{id}', [PropertyController::class, 'getControlsByPropertyId']);
  });

  Route::prefix('ultrasound')->group(function () {

    Route::post('/create', [UltrasoundController::class, 'create']);
    Route::post('/get', [UltrasoundController::class, 'get']);
    Route::post('/update', [UltrasoundController::class, 'update']);
  });

  Route::prefix('control-bovine')->group(function () {

    Route::post('/create', [ControlController::class, 'createControlBovine']);
    
  });

});

Route::prefix('report')->group(function () {

  Route::post('/insemination', [InseminationReportController::class, 'getInseminationReport']);
  Route::post('/bovine-history', [BovineReportController::class, 'generateBovineHistoryReport']);
  Route::post('/property-bovine-history', [BovineReportController::class, 'generatePropertyBovineHistoryReport']);

});


Route::middleware('auth:sanctum')->group(function () {


  Route::prefix('user')->group(function () {

    Route::get('/get', function (Request $request) {
      return $request->user();
    });

  });
  
});
