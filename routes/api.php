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
use App\Http\Controllers\Report\InseminationReportController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('management')->group(function () {

  Route::prefix('birth')->group(function () {

    Route::post('/create', [BirthController::class, 'create']);
  });

  Route::prefix('bovine')->group(function () {

    Route::post('/create', [BovineController::class, 'create']);   // crear bovino
    Route::post('/all',  [BovineController::class, 'all']);
    Route::post('/update', [BovineController::class , 'update']);
    Route::post('/delete', [BovineController::class , 'delete']);
    
  });

  Route::prefix('bull')->group(function () {

    Route::post('/create', [BullController::class, 'create']);   // crear toro
    Route::get('/all', [BullController::class, 'all']);          // listar toros
    Route::post('/exists', [BullController::class, 'exists']);
  });

  Route::prefix('confirmatory-ultrasound')->group(function () {

    Route::post('/create', [ConfirmatoryUltrasoundController::class, 'create']);
    Route::get('/all', [ConfirmatoryUltrasoundController::class, 'all']);
  });

  Route::prefix('protocol')->group(function () {

    Route::post('/start', [ControlController::class, 'startNewProtocol']);
  });

  Route::prefix('general-palpation')->group(function () {

    Route::post('/create', [GeneralPalpationController::class, 'create']);
    Route::get('/get', [GeneralPalpationController::class, 'get']);
  });

  Route::prefix('implant-retrieval')->group(function () {

    Route::post('/create', [ImplantRetrievalsController::class, 'create']);
    Route::get('/get', [ImplantRetrievalsController::class, 'get']);
  });

  Route::prefix('insemination')->group(function () {

    Route::post('/create', [InseminationController::class, 'create']);
    Route::post('/all', [InseminationController::class, 'all']);
    Route::put('/update/{id}', [InseminationController::class, 'update']);
    Route::delete('/delete', [InseminationController::class, 'delete']);
  });

  Route::prefix('pre-sincronizacion')->group(function () {

    Route::post('/create', [PresincronizationController::class, 'create']); // registrar pre-sincronización
    Route::get('/get', [PresincronizationController::class, 'get']);
  });

  Route::prefix('property')->group(function () {

    Route::get('/list', [PropertyController::class, 'listProperties']);
    Route::post('/create', [PropertyController::class, 'createProperty']);
    Route::put('/update/{id}', [PropertyController::class, 'updateProperty']);
    Route::delete('/delete/{id}', [PropertyController::class, 'deleteProperty']);
    Route::post('/name-exists', [PropertyController::class, 'nameExists']);
    Route::post('/isWorked', [PropertyController::class, 'isWorked']);


    Route::get('/{id}', [PropertyController::class, 'getPropertyById']);
    Route::post('/start-work', [PropertyController::class, 'startWork']);
    Route::post('/finish-work', [PropertyController::class, 'finishWork']);
  });

  Route::prefix('ultrasound')->group(function () {

    Route::post('/create', [UltrasoundController::class, 'create']); // registrar ecografía
    Route::get('/get', [UltrasoundController::class, 'get']);
  });
});

Route::prefix('report')->group(function () {

  Route::post('/insemination', [InseminationReportController::class, 'getInseminationReport']); // generar reporte de inseminación

});


Route::middleware('auth:sanctum')->group(function () {

  Route::prefix('user')->group(function () {

    Route::get('/get', function (Request $request) {
      return $request->user();
    });
  });
});
