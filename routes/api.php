<?php

use App\Http\Controllers\Authentication\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login',[AuthController::class,'login']);

Route::prefix('management')->group(function () {
   
  Route::prefix('user')->group(function(){

  });


  Route::prefix('birth')->group(function (){

   });

   Route::prefix('bovine')->group(function (){

   });

   Route::prefix('bull')->group(function(){

   });

   Route::prefix('confirmatory-ultrasound')->group(function(){

   });

   Route::prefix('protocolo')->group(function(){

   });

   Route::prefix('general-palpation')->group(function(){

   });

   Route::prefix('implant-retrieval')->group(function(){

   });

   Route::prefix('insemination')->group(function (){

   });

   Route::prefix('pre-sincronizacion')->group(function(){

   });

   Route::prefix('property')->group(function(){

   });

   Route::prefix('ultrasound')->group(function (){

   });


});

Route::prefix('report')->group(function (){
  
});


Route::middleware('auth:sanctum')->group(function () {
    
  Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
});