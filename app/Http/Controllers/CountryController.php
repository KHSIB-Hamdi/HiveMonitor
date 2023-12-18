<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respondWithSuccess(Country::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'iso_code_2' => 'required',
            'iso_code_3' => 'required',
            'country_code' => 'required'
        ]);

        $existing = Country::where('country_code', $data['country_code'])->first();

        if (!$existing) {
            $country = Country::create([
                'name' => $data['name'],
                'country_code' => $data['country_code'],
                'iso_code_2' => $data['iso_code_2'],
                'iso_code_3' => $data['iso_code_3']
            ]);
            return $country;
        }

        return response(['error' => 1, 'message' => 'country already exists'], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        return $country;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        if (!$country) {
            return response(['error' => 1, 'message' => 'country doesn\'t exist'], 404);
        }

        $country->name = $request->name ?? $country->name;
        $country->country_code = $request->country_code ?? $country->country_code;
        $country->update();
        return $country;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        $country->delete();
        return response(['error' => 0, 'message' => 'country has been deleted']);
    }
}
