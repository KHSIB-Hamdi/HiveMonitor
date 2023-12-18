<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respondWithSuccess(Site::all());
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
            'latitude' => 'required',
            'longitude' => 'required',
            'street' => 'required',
            'street_number' => 'required',
            'zip_code' => 'required',
            'city' => 'required',
            'value' => 'required'
        ]);

        $existing = Site::where('name', $data['name'])->first();

        if (!$existing) {
            $site = Site::create([
                'name' => $data['name'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'street' => $data['street'],
                'street_number' => $data['street_number'],
                'zip_code' => $data['zip_code'] , 
                'city' => $data['city'],
                'country_id' => $data['country_id'], 
            ]);
            return $site;
        }
        return response(['error' => 1, 'message' => 'site already exists'], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        return $this->respondWithSuccess($site);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        if (!$site) {
            return response(['error' => 1, 'message' => 'site doesn\'t exist'], 404);
        }

        $site->name = $request->name ?? $site->name;
        $site->latitude = $request->latitude ?? $site->latitude;
        $site->longitude = $request->longitude ?? $site->longitude;
        $site->street = $request->street ?? $site->street;
        $site->street_number = $request->street_number ?? $site->street_number;
        $site->zip_code = $request->zip_code ?? $site->zip_code;
        $site->city = $request->city ?? $site->city;
        $site->country_id = $request->country_id ?? $site->country_id;

        $site->update();

        return $site;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        $site->delete();
        return response(['error' => 0, 'message' => 'site has been deleted']);
    }
}

