<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::prefix('address')->group(function () {
    // Récupérer la liste des pays
    Route::get('/countries', [AddressController::class, 'getCountries'])->name('address.countries');

    // Récupérer les régions d'un pays spécifique
    Route::get('/regions/{country_id}', [AddressController::class, 'getRegions'])->name('address.regions');

    // Récupérer les villes d'une région spécifique
    Route::get('/cities/{region_id}', [AddressController::class, 'getCities'])->name('address.cities');

    // Ajouter une nouvelle adresse pour un utilisateur
    Route::post('/store', [AddressController::class, 'storeAddress'])->name('address.store');

    // Mettre à jour une adresse existante
    Route::put('/update/{id}', [AddressController::class, 'updateAddress'])->name('address.update');

    // Supprimer une adresse
    Route::delete('/delete/{id}', [AddressController::class, 'deleteAddress'])->name('address.delete');
});


Route::get('/countries', [AddressController::class, 'getCountriesWithRegions']);
Route::get('/countries/{countryId}/regions', [AddressController::class, 'getRegionsWithCities']);
Route::get('/regions/{regionId}/cities', [AddressController::class, 'getCitiesByRegion']);