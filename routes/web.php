<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JetController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes for Reservation management
    Route::resource('reservations', ReservationController::class);
    Route::patch('reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.updateStatus');
Route::get('reservations/{reservation}/confirmation', [ReservationController::class, 'confirmation'])->name('reservations.confirmation');
    Route::get('/reservations/{reservation}/pdf', [ReservationController::class, 'downloadPDF'])->name('reservations.pdf');
    Route::resource('jets', JetController::class);
    // Dans routes/web.php
Route::put('reservations/{reservation}', [ReservationController::class, 'updateStatus'])->name('reservations.updateStatus');

// Routes publiques
Route::get('check-status', [ReservationController::class, 'checkStatusPage'])->name('reservations.check-status');
Route::post('check-status', [ReservationController::class, 'checkStatus']);
Route::post('reservations/{reservation}/cancel', [ReservationController::class, 'cancelReservation'])->name('reservations.cancel');

});

require __DIR__ . '/auth.php';
