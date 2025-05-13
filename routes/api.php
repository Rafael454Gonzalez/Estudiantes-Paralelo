<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//importar los controladores
use App\Http\Controllers\Paralelocontroller;
use App\Http\Controllers\EstudianteController;



//Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//   return $request->user();
//});

Route::get('/index', [Paralelocontroller::class, 'index']);
Route::post('/store', [Paralelocontroller::class, 'store']);

Route::apiResource('estudiantes', EstudianteController::class);
Route::apiResource('paralelos', ParaleloController::class);

