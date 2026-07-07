<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

/*
|--------------------------------------------------------------------------
| Vehicle Dashboard
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-dashboard', 'pages::vehicles.dashboard.index')->name('vehicles-dashboard.index');
/*
|--------------------------------------------------------------------------
| Vehicle Types
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-types', 'pages::vehicles.types.index')->name('vehicles-types.index');
/*
|--------------------------------------------------------------------------
| Vehicle Owner
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-owner', 'pages::vehicles.owner.index')->name('vehicles-owner.index');
/*
|--------------------------------------------------------------------------
| Vehicle Driver
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-driver', 'pages::vehicles.driver.index')->name('vehicles-driver.index');
/*
|--------------------------------------------------------------------------
| Vehicle Documents
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-documents', 'pages::vehicles.documents.index')->name('vehicles-documents.index');
/*
|--------------------------------------------------------------------------
| Vehicle Loan
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-loans', 'pages::vehicles.loans.index')->name('vehicles-loans.index');
/*
|--------------------------------------------------------------------------
| Vehicle Insurance
|--------------------------------------------------------------------------
*/

    Route::livewire('vehicles-insurance', 'pages::vehicles.insurance.index')->name('vehicles-insurance.index');

/*
|--------------------------------------------------------------------------
| Vehicle Permit
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-permit', 'pages::vehicles.permit.index')->name('vehicles-permit.index');

/*
|--------------------------------------------------------------------------
| Vehicle PUCC
|--------------------------------------------------------------------------
*/

    Route::livewire('vehicles-pucc', 'pages::vehicles.pucc.index')->name('vehicles-pucc.index');

/*
|--------------------------------------------------------------------------
| Vehicle Fitness
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-fitness', 'pages::vehicles.fitness.index')->name('vehicles-fitness.index');

/*
|--------------------------------------------------------------------------
| Vehicle Tax
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-taxes', 'pages::vehicles.taxes.index')->name('vehicles-taxes.index');

/*
|--------------------------------------------------------------------------
| Vehicle GPS Tracker
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-gpstracker', 'pages::vehicles.gpstracker.index')->name('vehicles-gpstracker.index');

/*
|--------------------------------------------------------------------------
| Vehicle Fleet
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-fleet', 'pages::vehicles.fleet.index')->name('vehicles-fleet.index');


/*
|--------------------------------------------------------------------------
| Vehicle Fuel
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-fuels', 'pages::vehicles.fuels.index')->name('vehicles-fuels.index'); 


/*
|--------------------------------------------------------------------------
| Vehicle maintenance
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-maintenance', 'pages::vehicles.maintenance.index')->name('vehicles-maintenance.index'); 

/*
|--------------------------------------------------------------------------
| Vehicle Make / Brand
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-brand', 'pages::vehicles.brand.index')->name('vehicles-brand.index'); 
    
/*
|--------------------------------------------------------------------------
| Vehicle Model
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-modal', 'pages::vehicles.modal.index')->name('vehicles-modal.index'); 

/*
|--------------------------------------------------------------------------
| Vehicle Model Varient
|--------------------------------------------------------------------------
*/
    Route::livewire('vehicles-variant', 'pages::vehicles.variant.index')->name('vehicles-variant.index'); 

 


  

    
});