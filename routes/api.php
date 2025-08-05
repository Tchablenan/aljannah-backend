<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JetApiController;
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
Route::middleware('api')->group(function () {
    // Route pour obtenir toutes les réservations
    Route::get('reservations', [ReservationController::class, 'index']);

    // Route pour créer une réservation
    Route::post('reservations', [ReservationController::class, 'store']);

    // Route pour obtenir une réservation spécifique
    Route::get('reservations/{id}', [ReservationController::class, 'show']);

    // Route pour mettre à jour une réservation
    Route::put('reservations/{id}', [ReservationController::class, 'update']);

    // Route pour supprimer une réservation
    Route::delete('reservations/{id}', [ReservationController::class, 'destroy']);

    Route::get('/jets', [JetApiController::class, 'index']);
    Route::get('/jets/{id}', [JetApiController::class, 'show']);

});


