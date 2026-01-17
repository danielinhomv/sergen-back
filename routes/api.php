<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Management\BirthController;
use App\Http\Controllers\Management\BovineController;
use App\Http\Controllers\Management\BullController;
use App\Http\Controllers\Management\ConfirmatoryUltrasoundController;
use App\Http\Controllers\Management\ControlBovineController;
use App\Http\Controllers\Management\ControlController;
use App\Http\Controllers\Management\GeneralPalpationController;
use App\Http\Controllers\Management\ImplantRetrievalsController;
use App\Http\Controllers\Management\InseminationController;
use App\Http\Controllers\Management\PresincronizationController;
use App\Http\Controllers\Management\PropertyController;
use App\Http\Controllers\Management\UltrasoundController;
use App\Http\Controllers\Report\BovineReportController;
//reportes
use App\Http\Controllers\Report\DashboardGeneralReportController;

use App\Http\Controllers\Report\PresynchronizationReportController;
use App\Http\Controllers\Report\UltrasoundReportController;
use App\Http\Controllers\Report\ImplantRetrievalReportController;
use App\Http\Controllers\Report\InseminationReportController;
use App\Http\Controllers\Report\ConfirmatoryUltrasoundReportController;
use App\Http\Controllers\Report\GeneralPregnancyDiagnosisReportController;
use App\Http\Controllers\Report\BirthReportController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

Route::post('/user/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Rutas Protegidas por Login (SANCTUM)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    /* ===============================
       USER
    =============================== */
    Route::prefix('user')->group(function () {
        Route::get('/get', function (Request $request) {
            return $request->user();
        });

        Route::post('/logout', [AuthController::class, 'logout']);
    });

    /* ===============================
       MANAGEMENT
    =============================== */
    Route::prefix('management')->group(function () {

        /* BIRTH */
        Route::prefix('birth')->group(function () {
            Route::post('/create', [BirthController::class, 'create']);
            Route::post('/get', [BirthController::class, 'get']);
            Route::post('/update', [BirthController::class, 'update']);
        });

        /* BOVINE */
        Route::prefix('bovine')->group(function () {
            Route::post('/create', [BovineController::class, 'create']);
            Route::post('/all',  [BovineController::class, 'all']);
            Route::post('/update', [BovineController::class, 'update']);
            Route::post('/delete', [BovineController::class, 'delete']);
            Route::post('/get-by-serie', [BovineController::class, 'getBySerie']);
        });

        /* BULL */
        Route::prefix('bull')->group(function () {
            Route::post('/create', [BullController::class, 'create']);
            Route::post('/all', [BullController::class, 'all']);
            Route::post('/update', [BullController::class, 'update']);
            Route::post('/delete', [BullController::class, 'delete']);
        });

        /* CONFIRMATORY ULTRASOUND */
        Route::prefix('confirmatory-ultrasound')->group(function () {
            Route::post('/create', [ConfirmatoryUltrasoundController::class, 'create']);
            Route::post('/all', [ConfirmatoryUltrasoundController::class, 'all']);
            Route::post('/update', [ConfirmatoryUltrasoundController::class, 'update']);
            Route::post('/delete', [ConfirmatoryUltrasoundController::class, 'delete']);
        });

        /* PROTOCOL(GESTION) */
        Route::prefix('protocol')->group(function () {
            Route::post('/create', [ControlController::class, 'createControl']);
            Route::post('/update', [ControlController::class, 'updateControl']);
            Route::post('/get-last', [ControlController::class, 'getLastControl']);
            Route::post('/delete', [ControlController::class, 'deleteControl']);
        });

        /* GENERAL PALPATION */
        Route::prefix('general-palpation')->group(function () {
            Route::post('/create', [GeneralPalpationController::class, 'create']);
            Route::post('/get', [GeneralPalpationController::class, 'get']);
            Route::post('/update', [GeneralPalpationController::class, 'update']);
        });

        /* IMPLANT RETRIEVAL */
        Route::prefix('implant-retrieval')->group(function () {
            Route::post('/create', [ImplantRetrievalsController::class, 'create']);
            Route::post('/get', [ImplantRetrievalsController::class, 'get']);
            Route::post('/update', [ImplantRetrievalsController::class, 'update']);
        });

        /* INSEMINATION */
        Route::prefix('insemination')->group(function () {
            Route::post('/create', [InseminationController::class, 'create']);
            Route::post('/all', [InseminationController::class, 'all']);
            Route::post('/update', [InseminationController::class, 'update']);
            Route::post('/delete', [InseminationController::class, 'delete']);
        });

        /* PRESINCRONIZACION */
        Route::prefix('pre-sincronizacion')->group(function () {
            Route::post('/create', [PresincronizationController::class, 'create']);
            Route::post('/get', [PresincronizationController::class, 'get']);
            Route::post('/update', [PresincronizationController::class, 'update']);
        });

        /* PROPERTY */
        Route::prefix('property')->group(function () {
            Route::post('/list', [PropertyController::class, 'listProperties']);
            Route::post('/create', [PropertyController::class, 'createProperty']);
            Route::put('/update/{id}', [PropertyController::class, 'updateProperty']);
            Route::delete('/delete/{id}', [PropertyController::class, 'deleteProperty']);

            Route::get('/{id}', [PropertyController::class, 'getPropertyById']);
            Route::get('/controls/{id}', [PropertyController::class, 'getControlsByPropertyId']);
        });

        /* ULTRASOUND */
        Route::prefix('ultrasound')->group(function () {
            Route::post('/create', [UltrasoundController::class, 'create']);
            Route::post('/get', [UltrasoundController::class, 'get']);
            Route::post('/update', [UltrasoundController::class, 'update']);
        });

        /* CONTROL - BOVINE */
        Route::prefix('control-bovine')->group(function () {
            Route::post('/create', [ControlBovineController::class, 'create']);
        });
    });
});

/* ===============================
       REPORTES
    =============================== */
Route::prefix('report')->group(function () {

    Route::post('/bovine-history', [BovineReportController::class, 'generateBovineHistoryReport']);
    Route::post('/property-bovine-history', [BovineReportController::class, 'generatePropertyBovineHistoryReport']);

    // DASHBOARD
    Route::post('/dashboard', [DashboardGeneralReportController::class, 'index']);

    // IATF - LISTS
    Route::post('/presynchronization', [PresynchronizationReportController::class, 'index']);
    Route::post('/ultrasound', [UltrasoundReportController::class, 'index']);
    Route::post('/implant-retrieval', [ImplantRetrievalReportController::class, 'index']);
    Route::post('/insemination', [InseminationReportController::class, 'index']);
    Route::post('/confirmatory-ultrasound', [ConfirmatoryUltrasoundReportController::class, 'index']);
    Route::post('/general-palpation', [GeneralPregnancyDiagnosisReportController::class, 'index']);
    Route::post('/birth', [BirthReportController::class, 'index']);

    // IATF - PDF (export)
    Route::post('/presynchronization/export/pdf', [PresynchronizationReportController::class, 'export']);
    Route::post('/ultrasound/export/pdf', [UltrasoundReportController::class, 'export']);
    Route::post('/implant-retrieval/export/pdf', [ImplantRetrievalReportController::class, 'export']);
    Route::post('/insemination/export/pdf', [InseminationReportController::class, 'export']);
    Route::post('/confirmatory-ultrasound/export/pdf', [ConfirmatoryUltrasoundReportController::class, 'export']);
    Route::post('/general-palpation/export/pdf', [GeneralPregnancyDiagnosisReportController::class, 'export']);
    Route::post('/birth/export/pdf', [BirthReportController::class, 'export']);
});
