<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->respondWithSuccess(Sensor::all());
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
            'brand' => 'required',
            'model' => 'required',
            'serial_number' => 'required',
            'description' => 'required',
            'measurement_category_id' => 'required',
            'device_id' => 'required',
        ]);

        $existing = Sensor::where('serial_number', $data['serial_number'])->first();

        if (!$existing) {
            $sensor = Sensor::create([
                'brand' => $data['brand'],
                'model' => $data['model'],
                'serial_number' => $data['serial_number'],
                'description' => $data['description'],
                'measurement_category_id' => $data['measurement_category_id'],
                'device_id' => $data['device_id'],
            ]);
            return $sensor;
        }
        return response(['error' => 1, 'message' => 'device already exists'], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sensor  $sensor
     * @return \Illuminate\Http\Response
     */
    public function show(Sensor $sensor)
    {
        return $this->respondWithSuccess($sensor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sensor  $sensor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sensor $sensor)
    {
        if (!$sensor) {
            return response(['error' => 1, 'message' => 'device doesn\'t exist'], 404);
        }

        $sensor->brand = $request->brand ?? $sensor->brand;
        $sensor->model = $request->model ?? $sensor->model;
        $sensor->serial_number = $request->serial_number ?? $sensor->serial_number;
        $sensor->description = $request->description ?? $sensor->description;
        $sensor->measurement_category_id = $request->measurement_category_id ?? $sensor->measurement_category_id;
        $sensor->device_id = $request->device_id ?? $sensor->device_id;

        $sensor->update();

        return $sensor;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sensor  $sensor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        return response(['error' => 0, 'message' => 'sensor has been deleted']);
    }
}
