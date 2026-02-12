<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JetController;
use App\Http\Controllers\LuxuryServiceController;
use App\Http\Controllers\LuxuryPackageController;
use App\Http\Controllers\LuxuryPackageRequestController;

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
     return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class , 'index'])
     ->middleware(['auth', 'verified'])
     ->name('dashboard');

Route::middleware('auth')->group(function () {
     Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
     Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
     Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

     // Routes for Reservation management
     Route::resource('reservations', ReservationController::class);
     Route::patch('reservations/{reservation}/status', [ReservationController::class , 'updateStatus'])->name('reservations.updateStatus');
     Route::get('reservations/{reservation}/confirmation', [ReservationController::class , 'confirmation'])->name('reservations.confirmation');
     Route::get('/reservations/{reservation}/pdf', [ReservationController::class , 'downloadPDF'])->name('reservations.pdf');
     Route::resource('jets', JetController::class);

     // Routes for Jets management
     Route::resource('jets', JetController::class);

     // ============= CONCIERGERIE DE LUXE - BACKOFFICE =============

     // Dashboard principal de la conciergerie
     Route::get('luxury-dashboard', [LuxuryServiceController::class , 'dashboard'])
          ->name('admin.luxury.dashboard');

     // LUXURY SERVICES - Gestion des services
     Route::resource('luxury-services', LuxuryServiceController::class , [
          'names' => [
               'index' => 'admin.luxury.services.index',
               'create' => 'admin.luxury.services.create',
               'store' => 'admin.luxury.services.store',
               'show' => 'admin.luxury.services.show',
               'edit' => 'admin.luxury.services.edit',
               'update' => 'admin.luxury.services.update',
               'destroy' => 'admin.luxury.services.destroy'
          ]
     ]);

     Route::patch('luxury-services/{service}/toggle-status', [LuxuryServiceController::class , 'toggleStatus'])
          ->name('admin.luxury.services.toggle-status');

     Route::get('luxury-services/export', [LuxuryServiceController::class , 'export'])
          ->name('admin.luxury.services.export');

     // AJAX pour formulaires dynamiques
     Route::get('api/luxury-services/category/{categorie}', [LuxuryServiceController::class , 'getByCategory'])
          ->name('api.luxury.services.by-category');

     Route::post('api/luxury-services/{service}/calculate-price', [LuxuryServiceController::class , 'calculatePrice'])
          ->name('api.luxury.services.calculate-price');

     // LUXURY PACKAGES - Gestion des packages
     Route::resource('luxury-packages', LuxuryPackageController::class , [
          'names' => [
               'index' => 'admin.luxury.packages.index',
               'create' => 'admin.luxury.packages.create',
               'store' => 'admin.luxury.packages.store',
               'show' => 'admin.luxury.packages.show',
               'edit' => 'admin.luxury.packages.edit',
               'update' => 'admin.luxury.packages.update',
               'destroy' => 'admin.luxury.packages.destroy'
          ]
     ]);

     Route::patch('luxury-packages/{package}/toggle-status', [LuxuryPackageController::class , 'toggleStatus'])
          ->name('admin.luxury.packages.toggle-status');

     Route::patch('luxury-packages/{package}/toggle-visibility', [LuxuryPackageController::class , 'toggleVisibility'])
          ->name('admin.luxury.packages.toggle-visibility');

     Route::post('luxury-packages/{package}/duplicate', [LuxuryPackageController::class , 'duplicate'])
          ->name('admin.luxury.packages.duplicate');

     Route::post('luxury-packages/{package}/add-service', [LuxuryPackageController::class , 'addService'])
          ->name('admin.luxury.packages.add-service');

     Route::delete('luxury-packages/{package}/remove-service', [LuxuryPackageController::class , 'removeService'])
          ->name('admin.luxury.packages.remove-service');

     //Ajouter ces routes en plus du resource

     Route::get('admin/luxury/packages-export', [LuxuryPackageController::class , 'export'])->name('admin.luxury.packages.export');
     Route::post('admin/luxury/packages/{package}/duplicate', [LuxuryPackageController::class , 'duplicate'])->name('admin.luxury.packages.duplicate');
     Route::patch('admin/luxury/packages/{package}/toggle-status', [LuxuryPackageController::class , 'toggleStatus'])->name('admin.luxury.packages.toggle-status');
     Route::patch('admin/luxury/packages/{package}/toggle-visibility', [LuxuryPackageController::class , 'toggleVisibility'])->name('admin.luxury.packages.toggle-visibility');

     // LUXURY REQUESTS - Gestion des demandes
     Route::resource('luxury-requests', LuxuryPackageRequestController::class , [
          'names' => [
               'index' => 'admin.luxury.requests.index',
               'show' => 'admin.luxury.requests.show',
               'destroy' => 'admin.luxury.requests.destroy'
          ]
     ])->except(['create', 'store', 'edit', 'update']);

     Route::patch('luxury-requests/{request}/assign', [LuxuryPackageRequestController::class , 'assign'])
          ->name('admin.luxury.requests.assign');

     Route::patch('luxury-requests/{request}/status', [LuxuryPackageRequestController::class , 'updateStatus'])
          ->name('admin.luxury.requests.update-status');

     Route::patch('luxury-requests/{request}/priority', [LuxuryPackageRequestController::class , 'updatePriority'])
          ->name('admin.luxury.requests.update-priority');

     Route::post('luxury-requests/{request}/note', [LuxuryPackageRequestController::class , 'addNote'])
          ->name('admin.luxury.requests.add-note');

     Route::patch('luxury-requests/{request}/package', [LuxuryPackageRequestController::class , 'updatePackage'])
          ->name('admin.luxury.requests.update-package');

     Route::get('luxury-requests/export', [LuxuryPackageRequestController::class , 'export'])
          ->name('admin.luxury.requests.export');

     // Dans routes/web.php
     Route::put('reservations/{reservation}', [ReservationController::class , 'updateStatus'])->name('reservations.updateStatus');

     // Routes publiques
     Route::get('check-status', [ReservationController::class , 'checkStatusPage'])->name('reservations.check-status');
     Route::post('check-status', [ReservationController::class , 'checkStatus']);
     Route::post('reservations/{reservation}/cancel', [ReservationController::class , 'cancelReservation'])->name('reservations.cancel');


});




require __DIR__ . '/auth.php';