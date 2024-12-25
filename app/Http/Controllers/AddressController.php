<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Region;
use App\Models\City;
use App\Models\Address;
use App\Models\User;

class AddressController extends Controller
{
    /**
     * Afficher tous les pays.
     */
    public function getCountries()
    {
        $countries = Country::all();
        return response()->json($countries);
    }

    /**
     * Afficher les régions pour un pays donné.
     *
     * @param  int  $countryId
     */
    public function getRegions($countryId)
    {
        $regions = Region::where('country_id', $countryId)->get();
        return response()->json($regions);
    }

    /**
     * Afficher les villes pour une région donnée.
     *
     * @param  int  $regionId
     */
    public function getCities($regionId)
    {
        $cities = City::where('region_id', $regionId)->get();
        return response()->json($cities);
    }

    /**
     * Enregistrer une adresse pour un utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            // 'user_id' => 'required|exists:users,id',
            'country_id' => 'required|exists:countries,id',
            'region_id' => 'required|exists:regions,id',
            'city_id' => 'required|exists:cities,id',
            'address_line' => 'nullable|string|max:255',
        ]);

        $address = Address::create([
            // 'user_id' => $request->user_id,
            'country_id' => $request->country_id,
            'region_id' => $request->region_id,
            'city_id' => $request->city_id,
            'address_line' => $request->address_line,
        ]);

        return response()->json([
            'message' => 'Adresse enregistrée avec succès',
            'address' => $address
            ], 201);
    }

    /**
     * Mettre à jour une adresse existante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'country_id' => 'nullable|exists:countries,id',
            'region_id' => 'nullable|exists:regions,id',
            'city_id' => 'nullable|exists:cities,id',
            'address_line' => 'nullable|string|max:255',
        ]);

        $address = Address::findOrFail($id);

        $address->update($request->only(['country_id', 'region_id', 'city_id', 'address_line']));

        return response()->json(['message' => 'Adresse mise à jour avec succès', 'address' => $address]);
    }

    /**
     * Supprimer une adresse.
     *
     * @param  int  $id
     */
    public function deleteAddress($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();

        return response()->json(['message' => 'Adresse supprimée avec succès']);
    }

    public function getCountriesWithRegions()
{
    $countries = Country::with('regions')->get();
    return response()->json($countries);
}

public function getRegionsWithCities($countryId)
{
    $regions = Region::where('country_id', $countryId)
                     ->with('cities')
                     ->get();
    return response()->json($regions);
}


public function getCitiesByRegion($regionId)
{
    $cities = City::where('region_id', $regionId)->get();
    return response()->json($cities);
}

}
