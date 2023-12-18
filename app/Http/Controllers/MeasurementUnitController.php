<?php

namespace App\Http\Controllers;

use App\Models\MeasurementUnit;
use Illuminate\Http\Request;

class MeasurementUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respondWithSuccess(MeasurementUnit::all());
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
            'unit' => 'required',
            'description' => 'required'
        ]);

        $existing = MeasurementUnit::where('name', $data['name'])->first();

        if (!$existing) {
            $measurementUnit = MeasurementUnit::create([
                'name' => $data['name'],
                'unit' => $data['unit'],
                'description' => $data['description'],
            ]);
            return $measurementUnit;
        }
        return response(['error' => 1, 'message' => 'measurement unit already exists'], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MeasurementUnit  $measurementUnit
     * @return \Illuminate\Http\Response
     */
    public function show(MeasurementUnit $measurementUnit)
    {
        return $this->respondWithSuccess($measurementUnit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MeasurementUnit  $measurementUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MeasurementUnit $measurementUnit)
    {
        if (!$measurementUnit) {
            return response(['error' => 1, 'message' => 'measurement unit doesn\'t exist'], 404);
        }

        $measurementUnit->name = $request->name ?? $measurementUnit->name;
        $measurementUnit->unit = $request->unit ?? $measurementUnit->unit;
        $measurementUnit->description = $request->description ?? $measurementUnit->description;

        $measurementUnit->update();

        return $measurementUnit;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MeasurementUnit  $measurementUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy(MeasurementUnit $measurementUnit)
    {
        $measurementUnit->delete();
        return response(['error' => 0, 'message' => 'measurement unit has been deleted']);
    }
}

