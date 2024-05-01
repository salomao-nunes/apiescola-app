<?php

use App\Http\Controllers\EscolaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::controller(EscolaController::class)->group(function(){

    Route::get('/escolas','index');
    Route::post('/cadastrar-escola', 'store');
    Route::get('/escola/{id}', 'show');
    Route::put('/escola/{id}', 'update');
    Route::delete('/escola/{id}', 'destroy');
    Route::get('/provincias', 'provinces');
    Route::post('/escolas/upload-excell', 'uploadXLSX');

});