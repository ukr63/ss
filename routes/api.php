<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DatabaseController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('db')->group(function () {
    Route::get('/', [DatabaseController::class, 'index']);
    Route::post('/upload', [DatabaseController::class, 'upload']);
    Route::delete('/project/{id}', [DatabaseController::class, 'delete']);
    Route::get('/project/{id}', [DatabaseController::class, 'detail']);
    Route::post('/project/merge', [DatabaseController::class, 'merge']);
    Route::get('/project/download/{id}', [DatabaseController::class, 'downloadProject']);
    Route::get('/project/download/file/{id}', [DatabaseController::class, 'downloadProjectFile']);
});
